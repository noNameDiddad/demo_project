<?php

namespace App\Connectors;

use Psr\Http\Message\ResponseInterface;

interface ConnectorInterface
{
    public function sendRequest(string $url, array $options, string $method = 'POST'): ResponseInterface;
    public function setOptions(array $params = [], array $headers = [], bool $isHaveMultipart = false): array;
}
