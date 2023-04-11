<?php

namespace App\EventHandlers;

use App\DTO\EventParserDTO;
use App\Models\Event;

interface EventHandlerInterface
{
    public function handle(EventParserDTO $event, Event $parent);
}
