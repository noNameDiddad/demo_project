<?php

namespace App\Connectors;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

abstract class Connector implements ConnectorInterface
{
    public string $serviceUrl;

    /**
     * Метод отправки запроса к удалённому серверу
     *
     * @param string $url - адрес сервера
     * @param array $options - опции содержащие в себе все данные для передачи
     * @param string $method - метод запроса
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function sendRequest(string $url, array $options, string $method = 'POST'): ResponseInterface
    {
        try {
            $client = new Client();
            $response = $client->request($method, $this->serviceUrl.$url, $options);
        } catch (GuzzleException $ex) {
            Log::error("Ошибка отправки sendRequest: ". $ex->getMessage());
            Log::error("Параметры ошибки sendRequest: ", ['url' => $url, 'options' => $options]);
            throw $ex;
        }

        return $response;
    }

    /**
     *  Настройка опций для отправки запроса к удалённому серверу
     *
     * @param array $params - параметры метода POST
     * @param array $headers - заголовки
     * @param bool $isHaveMultipart - условие передачи файлов
     * @return array
     */
    abstract public function setOptions(array $params = [], array $headers = [], bool $isHaveMultipart = false): array;
}
