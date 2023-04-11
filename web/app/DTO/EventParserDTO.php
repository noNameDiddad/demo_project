<?php

namespace App\DTO;

use App\Models\Event;

class EventParserDTO implements DTOInterface
{
    public string $code;
    public Event $original;
    public array|null $data;
    public int $status;
    public int|null $organization_id;
    public string|null $errors;
    public string|null $parent;
    public string $event_id;

    public function __construct($event)
    {
        $this->setProperties($event);
    }

    public function setProperties($event)
    {
        $this->original = $event;
        $this->code = $event->code;
        $this->data = json_decode(data_get($event, 'data'), true);
        $this->parent = $event->parent ?? null;
        $this->event_id = $event->event_id ?? null;
        $this->status = $event->status;
        $this->organization_id = $event->organization_id;
        $this->errors = json_encode(data_get($event, 'errors'));
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'data' => $this->data,
            'parent' => $this->parent,
            'event_id' => $this->event_id,
            'status' => $this->status,
            'organization_id' => $this->organization_id,
            'errors' => $this->errors,
        ];
    }
}
