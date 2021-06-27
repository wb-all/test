<?php

declare(strict_types=1);

namespace App\Common\Application\Http;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractAction
{
    protected function json(array $data, int $status = 200): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write(json_encode($data, JSON_THROW_ON_ERROR));

        return $response->withAddedHeader('content-type', 'application/json')->withStatus($status);
    }

    protected function parseJson(string $body): array
    {
        $data = json_decode($body, true);

        if (! $data) {
            throw new \RuntimeException('Bad request.');
        }

        return $data;
    }
}
