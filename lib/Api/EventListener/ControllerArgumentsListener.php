<?php

namespace Api\EventListener;

use Api\Annotations\Resource;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerArgumentsEvent;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ControllerArgumentsListener
 * @package Api\EventListener
 */
class ControllerArgumentsListener
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ControllerArgumentsListener constructor.
     *
     * @param SerializerInterface $serializer
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->annotationReader = new AnnotationReader();
        $this->serializer = $serializer;
    }

    /**
     * @param FilterControllerArgumentsEvent $event
     *
     * @throws \ReflectionException
     */
    public function onControllerArguments(FilterControllerArgumentsEvent $event)
    {
        $controller = explode('::', $event->getRequest()->get('_controller'));
        if (count($controller) != 2) {
            return;
        }

        $action = $controller[1];
        $controller = $controller[0];

        $resource = $this->getMethodAnnotation($controller, $action, Resource::class);

        if ($resource instanceof Resource) {
            $this->transformParameters($resource, $event->getRequest());
        }
    }

    /**
     * @param string $class
     * @param string $method
     * @param string $annotationName
     *
     * @return null|object
     * @throws \ReflectionException
     */
    private function getMethodAnnotation(string $class, string $method, string $annotationName)
    {
        $reflectionMethod = new \ReflectionMethod($class, $method);
        return $this->annotationReader->getMethodAnnotation($reflectionMethod, $annotationName);
    }

    /**
     * @param Resource $resource
     * @param Request  $request
     */
    public function transformParameters(Resource $resource, Request $request)
    {
        $entity = $request->getContent();
        if ($resource->type == 'model_data') {
            $entity = $this->deserialize($request->getContent(), $resource->class);
        }
        $request->request->set($resource->reference, $entity);
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