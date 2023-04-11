<?php

namespace App\Services;

use App\Connectors\AstralConnector;
use App\Connectors\SimpleConnector;
use App\DTO\EventParserDTO;
use App\Models\Event;
use App\Models\User;
use App\Repositories\EventRepository;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\BindingResolutionException;


class SimpleConnectorService
{

    public SimpleConnector $connector;
    /**
     * @var mixed
     */
    private mixed $user;

    const CONSUMER_REG = ['reg'];



    /**
     * @throws BindingResolutionException
     */
    public function __construct(User $user)
    {
        $env = $user->env ? SimpleConnector::TEST_ENV : SimpleConnector::BASE_ENV;
        $this->connector = new SimpleConnector($env);
    }

    /**
     * Метод публикации событий в Астрале
     *
     * @param array $data - Данные для события
     * @param string $code - Код события Астрала
     * @param array $consumer - Владелец события
     * @param int $status - Статус состояни события Астрала для медиума
     * @param int|null $user_id - ID организации отправившей события
     * @param string $parent - ID родительского события
     * @return EventParserDTO
     * @throws GuzzleException
     */
    public function storeEvents(mixed $data, string $code, array $consumer, int $status, int $user_id = null , string $parent = ''): EventParserDTO
    {
        $event = json_decode($this->connector->storeEvents($data, $code, $consumer, $parent)->getBody()->getContents())->events[0];
        if ($user_id == null)
            $user_id = $this->user->id;
        return new EventParserDTO(EventRepository::save($code, $event, $status, $user_id, $parent));

    }

    /**
     * Метод получения опубликованных событий
     *
     * @return mixed
     * @throws GuzzleException
     */
    public function getEvents(): mixed
    {
        try {
            return json_decode($this->connector->getEvents()->getBody()->getContents())->events;
        } catch (ConnectException $exception) {
            return null;
        }
    }


    /**
     * Метод получения неподтверждённых событий
     *
     * @return mixed
     * @throws GuzzleException
     */
    public function getUnconfirmedEvents(): mixed
    {
        return json_decode($this->connector->getUnconfirmedEvents()->getBody()->getContents())->events;
    }

    /**
     * Метод подтверждения опубликованных событий
     *
     * @param array $confirmedEvents - Массив с ID неподтверждённых событий
     * @return void
     * @throws GuzzleException
     */
    public function confirmEvents(array $confirmedEvents): void
    {
        $this->connector->confirmEvents($confirmedEvents);
    }

    /**
     * Метод получения событий по их ID
     *
     * @param string $eventUuid - ID события
     * @return mixed
     * @throws GuzzleException
     */
    public function getEventByEventUuid(string $eventUuid): mixed
    {
        return json_decode($this->connector->getEventByEventUuid($eventUuid)->getBody()->getContents())->events[0];
    }

    /**
     * Метод публикации событий в Астрале с предустановленными статусом и получателем REG:
     *
     * status: STATUS_WAITING,
     *
     * @param array $data - Данные для события
     * @param string $code - Код события Астрала
     * @param EventParserDTO|null $event - родительское событие
     * @param int|null $user_id - организация
     * @param string $parent
     * @return EventParserDTO
     * @throws GuzzleException
     */
    public function storeRegEvent(array $data, string $code, EventParserDTO $event = null, int $user_id = null, string $parent = ''): EventParserDTO
    {
        return $this->storeEvents(
            $data,
            $code,
            SimpleConnectorService::CONSUMER_REG,
            Event::STATUS_WAITING,
            data_get($event, 'user_id', $user_id),
            data_get($event, 'event_id', $parent)
        );
    }

    public function setEnv(string $string): void
    {
        $this->connector->setEnv($string);
    }
}
