<?php

namespace App\Services;

use App\Entities\ObjectType;
use App\EventHandlers\EventHandler;
use App\Models\Event;
use App\Repositories\EventRepository;
use App\Repositories\OrganizationRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class SimpleService
{
    /**
     * Metod doing something
     *
     * @param mixed $events
     * @return bool
     */
    public static function somethingDo(mixed $events): bool
    {
        return false;
    }

}

