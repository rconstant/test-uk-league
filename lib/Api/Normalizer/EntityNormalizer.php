<?php

namespace Api\Normalizer;

use Api\Exception\ValidatorException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class EntityNormalizer
 * @package Api\Normalizer
 */
class EntityNormalizer extends ObjectNormalizer
{
    protected $entityManager;

    /**
     * EntityNormalizer constructor.
     *
     * @param EntityManagerInterface              $entityManager
     * @param null|ClassMetadataFactoryInterface  $classMetadataFactory
     * @param null|NameConverterInterface         $nameConverter
     * @param null|PropertyAccessorInterface      $propertyAccessor
     * @param null|PropertyTypeExtractorInterface $propertyTypeExtractor
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ?ClassMetadataFactoryInterface $classMetadataFactory = null,
        ?NameConverterInterface $nameConverter = null,
        ?PropertyAccessorInterface $propertyAccessor = null,
        ?PropertyTypeExtractorInterface $propertyTypeExtractor = null
    ) {
        $this->entityManager = $entityManager;

        parent::__construct($classMetadataFactory, $nameConverter, $propertyAccessor, $propertyTypeExtractor);
    }

    /**
     * @param      $data
     * @param      $type
     * @param null $format
     *
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return (class_exists($type)) &&
            (is_numeric($data) || is_string($data) || (is_array($data) && isset($data['id'])));
    }

    /**
     * @param       $data
     * @param       $class
     * @param null  $format
     * @param array $context
     *
     * @return null|object
     * @throws \ReflectionException
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (!$entity = $this->entityManager->find($class, $data))
        {
            $reflectionClass = new \ReflectionClass($class);
            $message = $reflectionClass->getShortName() . " " . $data ." not found";

            $constraintList = new ConstraintViolationList();
            $constraintList->add(new ConstraintViolation($message, $message, [], null, strtolower($reflectionClass->getShortName()), $data));

            throw new ValidatorException($constraintList);
        }
        return $entity;
    }
}