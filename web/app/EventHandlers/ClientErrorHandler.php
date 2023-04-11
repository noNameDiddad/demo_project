<?php

namespace App\EventHandlers;

use App\DTO\EventParserDTO;
use App\Entities\ObjectType;
use App\Models\Event;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

class ClientErrorHandler implements EventHandlerInterface
{
    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function handle(EventParserDTO $event, Event $parent)
    {
        $handler = new (ObjectType::getObjectByType($parent->code))();
        $handler->errorHandle($event, $parent);
    }
}
