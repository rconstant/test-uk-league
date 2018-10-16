<?php

namespace Api\EventListener;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class ViewListener
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

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