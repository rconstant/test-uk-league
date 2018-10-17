<?php

namespace Api\EventListener;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

/**
 * Class ViewListener
 * @package Api\EventListener
 */
class ViewListener
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ViewListener constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     *
     * @return GetResponseForControllerResultEvent
     */
    public function onView(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();

        if (!$result instanceof Response) {
            switch ($event->getRequest()->getMethod()) {
                case 'POST':
                    $statusCode = Response::HTTP_CREATED;
                    break;
                case 'DELETE':
                    $statusCode = Response::HTTP_NO_CONTENT;
                    break;
                default:
                    $statusCode = Response::HTTP_OK;
            }

            $response = new Response($this->serializer->serialize($result, 'json'), $statusCode);
            $response->headers->set('Content-Type', 'application/json');
            $event->setResponse($response);
        }

        return $event;
    }
}