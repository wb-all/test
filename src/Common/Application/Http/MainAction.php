<?php

declare(strict_types=1);

namespace App\Common\Application\Http;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class MainAction
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $rootPath = str_replace('/public', '', $request->getServerParams()['DOCUMENT_ROOT']);

        $response = new Response();
        $response->getBody()
            ->write(file_get_contents($rootPath . '/templates/main.html'));

        return $response;
    }
}