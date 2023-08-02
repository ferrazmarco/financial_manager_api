<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class ExceptionListener
{
    #[AsEventListener]
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $data = $this->sanitizeMessage($exception->getMessage());
        $response = new JsonResponse($data);

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
        } else {
            $response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }

    private function sanitizeMessage(string $message): array
    {
        $decoded = json_decode($message);

        return ['error' => $decoded ?? $message];
    }
}
