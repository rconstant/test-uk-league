<?php

namespace Api\EventListener;

use Api\Exception\ApiException;
use Api\Exception\ValidatorException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{
    public function onException(GetResponseForExceptionEvent $event)
    {
        $exception =  $event->getException();
        $statusCode = 500;
        $message = $exception->getMessage();

        if ($exception instanceof ValidatorException) { // my custom exception with form object
            $statusCode = 400;
            $message = $exception->toArray();
        } elseif ($exception instanceof NotFoundHttpException) {
            $statusCode = $exception->getStatusCode();
            $message = $this->toArray($exception->getStatusCode(), $exception->getMessage());
        } elseif ($exception instanceof ApiException) {
            $statusCode = $exception->getStatusCode();
            $message = $exception->toArray();
        }
        $content = json_encode($message);
        $response = new Response($content, $statusCode);
        $response->headers->set('Content-Type', 'application/json');
        $event->setResponse($response);
    }

    private function toArray($code, $message)
    {
        return [
            'code' => $code,
            'message' => $message
        ];
    }
}