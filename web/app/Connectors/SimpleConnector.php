<?php

namespace App\Connectors;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\ResponseInterface;

class SimpleConnector extends Connector
{
    const BASE_ENV= 'base';
    const TEST_ENV = 'test';

    const LISTENER_ENV = [
        self::BASE_ENV,
        self::TEST_ENV
    ];

    private mixed $apiKey;

    public function __construct($env = self::BASE_ENV)
    {
        $this->setEnv($env);
    }

    /**
     * Метод публикации событий в Астрале
     *
     * @param array $data - Данные для события
     * @param string $code - Код события Астрала
     * @param array $consumer - Владелец события
     * @param string $parent - ID родительского события
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function storeEvents(mixed $data, string $code, array $consumer, string $parent = ''): ResponseInterface
    {
        $events[] = [
            'code' => $code,
            'consumer' => $consumer,
            'data' => $data,
            'parent' => $parent
        ];
        $params = ['events' => $events];
        return $this->sendRequest('/events', $this->setOptions($params));
    }

    /**
     * Метод получения опубликованных событий
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function getEvents(): ResponseInterface
    {
        return $this->sendRequest('/events', $this->setOptions(), 'GET');
    }

    /**
     * Метод получения событий по их ID
     *
     * @param string $eventUuid - ID события
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function getEventByEventUuid(string $eventUuid): ResponseInterface
    {
        return $this->sendRequest('/events/'.$eventUuid, $this->setOptions(), 'GET');
    }


    /**
     * Метод получения неподтверждённых событий
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function getUnconfirmedEvents(): ResponseInterface
    {
        return $this->sendRequest('/events/unconfirmed', $this->setOptions(), 'GET');
    }


    /**
     * Метод подтверждения опубликованных событий
     *
     * @param array $eventsIds - Массив с ID неподтверждённых событий
     * @return void
     * @throws GuzzleException
     */
    public function confirmEvents(array $eventsIds): void
    {
        if (count($eventsIds) <> 0) {
            $this->sendRequest('/events/confirm',$this->setOptions(['events_id' => $eventsIds]) );
        }
    }

    public function setOptions(array $params = [], array $headers = [], bool $isHaveMultipart = false): array
    {
        $headers += ['X-API-Key' => $this->apiKey];
        $options = [
            'headers' => $headers,
            'connect_timeout' => 600,
        ];

        return $isHaveMultipart ? $options + ['multipart' => $params] : $options + ['json' => $params];
    }

    public function setEnv(string $string)
    {
        switch ($string) {
            case self::BASE_ENV:
                $this->serviceUrl = config('services.simple.base_url') . ':' . config('services.simple.base_port');
                $this->apiKey = config('services.simple.base_api_key');
                break;
            case self::TEST_ENV:
                $this->serviceUrl = config('services.simple.optional_url') . ':' . config('services.simple.base_port');
                $this->apiKey = config('services.simple.optional_api_key');
                break;
        }
    }
}
