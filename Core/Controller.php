<?php

namespace Core;

use Core\Request;
use Core\Response;

abstract class Controller
{
    protected function getRequestBody(): ?array
    {
        return (new Request())
            ->getBody();
    }

    protected function response(?int $statusCode = null, ?array $data = null): Response
    {
        $response = new Response();

        if ($statusCode !== null && $data !== null) {
            $response->setStatusCode($statusCode)
                ->json($data)
                ->send();
        }

        return $response;
    }
}
