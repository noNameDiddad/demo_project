<?php

namespace App\Repositories;

use App\DTO\ModelDTO\EventDTO;
use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class EventRepository
{
    /**
     * @param $code
     * @param $event
     * @param $status
     * @param int $organization_id
     * @param string|null $parent_id
     * @return Event
     */
    public static function save($code, $event, $status, int $organization_id = null, string $parent_id = null): Event
    {
        return Event::create((new EventDTO(
            $code,
            $event,
            $status,
            $organization_id,
            $parent_id
        ))->toArray());
    }

    /**
     * @param $event_id
     * @return Event|null
     */
    public static function firstByAstralId($event_id): Event|null
    {
        return Event::where('event_id', $event_id)->first();
    }

    /**
     * @param $event_id
     * @return Event|null
     */
    public static function firstChildByAstralId($event_id): Event|null
    {
        return Event::where('parent', $event_id)->first();
    }

    /**
     * @param string $code
     * @return Collection|array
     */
    public static function getByCode(string $code): Collection|array
    {
        return Event::query()->where('code', $code)->get();
    }


    /**
     * @param string $code
     * @return Collection|array
     */
    public static function getFreshByCode(string $code): Collection|array
    {
        return Event::query()->where('code', $code)
            ->orderBy('created_at','desc')
            ->whereDate('created_at', Carbon::now()->subMinutes(1))
            ->get();
    }

    /**
     * @param $event
     * @param $key
     * @return mixed
     */
    public static function getDataElement($event, $key): mixed
    {
        return data_get(json_decode($event->data), $key);
    }
}
