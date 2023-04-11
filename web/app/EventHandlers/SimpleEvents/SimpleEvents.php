<?php

namespace App\EventHandlers\SimpleEvents;

use App\DTO\EventParserDTO;
use App\EventHandlers\EventHandlerInterface;
use App\Services\DocumentService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class SimpleEvents implements EventHandlerInterface
{
    protected SimpleService $simpleService;

    public function __construct()
    {
        $this->simpleService = new SimpleService();
    }

    /**
     * @throws GuzzleException
     */
    public function handle(EventParserDTO $event, $parent = null)
    {
        $this->simpleService->somethingDo($event);
    }
}
