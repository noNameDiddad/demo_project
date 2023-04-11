<?php

namespace App\Entities;

use App\EventHandlers\ClientErrorHandler;
use App\EventHandlers\SimpleEvents\Docflow\EdoDocflowHandler;
use App\EventHandlers\SimpleEvents\Docflow\EdoDocflowStatusChangedHandler;
use App\EventHandlers\SimpleEvents\Docflow\EdoDocflowTransactionNotifyHandler;
use App\EventHandlers\SimpleEvents\Draft\EdoDraftImportedHandler;
use App\EventHandlers\SimpleEvents\Draft\EdoDraftInfoHandler;
use App\EventHandlers\SimpleEvents\Draft\EdoDraftRecipientSetHandler;
use App\EventHandlers\SimpleEvents\EdoIncomingHandler;
use App\EventHandlers\SimpleEvents\EdoPrintformResponseHandler;
use App\EventHandlers\SimpleEvents\SimpleEvents;
use App\EventHandlers\SimpleEvents\File\EdoFileImportHandler;
use App\EventHandlers\SimpleEvents\Invitation\EdoInvitationAnswerRequestHandler;
use App\EventHandlers\SimpleEvents\Invitation\EdoInvitationFindResponseHandler;
use App\EventHandlers\SimpleEvents\Invitation\EdoInvitationInfoHandler;
use App\EventHandlers\SimpleEvents\Invitation\EdoInvitationSendRequestHandler;
use App\EventHandlers\EPD\UuidNewResponseHandler;
use App\EventHandlers\REG\RegEditResponseHandler;
use App\EventHandlers\REG\RegNotifyRoutesHandler;
use App\EventHandlers\REG\RegRequestHandler;
use App\EventHandlers\REG\RegResponseHandler;
use Exception;

class ObjectType
{
    private const EVENTS_TYPES = [
        'main.event' => MainEvent::class,
    ];

    /**
     * @throws Exception
     */
    public static function getObjectByType($type): ?string
    {
        if (array_key_exists($type, self::EVENTS_TYPES))
            return self::EVENTS_TYPES[$type];

        return throw new Exception();
    }

}
