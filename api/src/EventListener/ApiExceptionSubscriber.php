<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\ConstraintViolationInterface;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $e = $event->getException();
        if ($e instanceof \App\Exception\ValidationException) {
            $arr = [];
            /** @var ConstraintViolationInterface $error */
            foreach ($e->getErrors() as $error) {
                $arr[$error->getPropertyPath()] = $error->getMessage();
            }
            $response = new JsonResponse(['errors' => $arr], JsonResponse::HTTP_BAD_REQUEST);
            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }
}
