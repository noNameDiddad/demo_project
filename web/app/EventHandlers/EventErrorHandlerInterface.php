<?php

namespace App\EventHandlers;

use App\DTO\EventParserDTO;
use App\Models\Event;

interface EventErrorHandlerInterface
{
    public function errorHandle(EventParserDTO $event, Event $parent);
}
