<?php

namespace App\Services;

use App\Entities\ObjectType;
use App\EventHandlers\EventHandler;
use App\Models\Event;
use App\Repositories\EventRepository;
use App\Repositories\OrganizationRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class EventObserverService
{
    /**
     * @param mixed $events
     * @return array
     */
    public static function handleEvents(mixed $events): array
    {
        $confirmedEvents = [];
        foreach ($events as $event) {
            try {
                self::handleEvent($event);
                $confirmedEvents[] = $event->id;
            } catch (Exception $ex) {
                Log::error($ex->getMessage());
                Log::info(json_encode($event, JSON_PRETTY_PRINT));
            }
        }
        return $confirmedEvents;
    }

    /**
     * @param mixed $event
     * @return void
     */
    public static function handleEvent(mixed $event): void
    {
        try {
            $parent = EventRepository::firstByAstralId(data_get($event, 'parent', ''));
            if ($parent == null) $parent = new Event();
            new EventHandler($event, new (ObjectType::getObjectByType($event->code))(), $parent);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            Log::info(json_encode($event, JSON_PRETTY_PRINT));
        }
    }

    public function saveEvent($event, $parent): Event
    {
        return EventRepository::save(
            $event->code,
            $event,
            Event::STATUS_ERROR,
            $this->getOrganizationId($event, $parent),
            $parent->event_id
        );
    }

    private function getOrganizationId($event, $parent)
    {
        if (($organization_id = $parent?->organization_id) == null) {
            if (property_exists($event->data, 'ИдАбонента')) {
                return $this->getOrganizationIdByEvent($event);
            }
        }
        return $organization_id;
    }


    private function getOrganizationIdByEvent($event)
    {
        return OrganizationRepository::getOrganization($event->data->ИдАбонента)->id;
    }


    /**
     * @param Event $event
     * @param null $code
     * @param null $astralId
     * @return Event|null
     */
    public static function recursiveSearchEventByCodeOrAstralId(Event $event, $code = null, $astralId = null): ?Event
    {
        if ($event->code == $code || $event->event_id == $astralId) {
            return $event;
        } else {
            if ($event->parentEvent === null) return null;
            return self::recursiveSearchEventByCodeOrAstralId($event->parentEvent, $code, $astralId);
        }
    }
//
//    public static function resendEvent(Event $event, $consumer)
//    {
//        $astralConnectorService = new AstralConnectorService();
//        return $astralConnectorService->storeEvents(
//            json_decode($event->data),
//            $event->code,
//            $consumer,
//            Event::STATUS_WAITING,
//            $event->organization_id,
//            $event->parent
//        );
//    }
}

