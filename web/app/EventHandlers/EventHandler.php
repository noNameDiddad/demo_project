<?php

namespace App\EventHandlers;

use App\DTO\EventParserDTO;
use App\Models\Event;
use App\Services\EventObserverService;

class EventHandler
{
    private EventObserverService $service;

    public function __construct($event, private EventHandlerInterface $handler, Event $parent)
    {
        $this->service = new EventObserverService();
        $this->handle($event, $parent);
    }

    private function handle($event, Event $parent): void
    {
        $savedEvent = $this->service->saveEvent($event, $parent);
        $this->handler->handle((new EventParserDTO($savedEvent)), $parent);
    }
}
