<?php

namespace App\Service;

use Api\Exception\ApiException;
use Api\Exception\ValidatorException;
use App\Entity\Team;
use App\Entity\TeamInterface;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class TeamService
 * @package App\Service
 */
class TeamService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * TeamService constructor.
     *
     * @param EntityManagerInterface $em
     * @param ValidatorInterface     $validator
     * @param SerializerInterface    $serializer
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    /**
     * @param Team $data
     *
     * @return Team
     */
    public function create(Team $data): Team
    {
        $errors = $this->validator->validate($data);
        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        try {
            $this->em->persist($data);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new ApiException($e->getCode(), $e->getMessage());
        }

        return $data;
    }

    /**
     * @param Team   $team
     * @param string $data
     *
     * @return TeamInterface
     */
    public function update(Team $team, string $data): TeamInterface
    {
        /**
         * @var TeamInterface $entity
         */
        $entity = $this->deserialize($data, Team::class, $team);
        $errors = $this->validator->validate($entity);
        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        try {
            $this->em->merge($entity);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new ApiException($e->getCode(), $e->getMessage());
        }

        return $entity;
    }

    /**
     * @param string $encodedData
     * @param string $type
     * @param null   $source
     *
     * @return object
     */
    private function deserialize(string $encodedData, string $type, $source = null)
    {
        $context = [];
        if (!is_null($source)) {
            $context['object_to_populate'] = clone $source;
        }
        return $this->serializer->deserialize($encodedData, $type, 'json', $context);
    }
}