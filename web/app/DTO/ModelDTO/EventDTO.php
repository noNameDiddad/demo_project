<?php

namespace App\DTO\ModelDTO;

use App\DTO\DTOInterface;
use App\Models\Event;

class EventDTO implements DTOInterface
{
    public string $code;
    public string $data;
    public string|null $event_id;
    public int $status;
    public int|null $organization_id;
    public string|null $errors;
    private string|null $parent;

    public function __construct($code, $event, $status, $organization_id, $parent_id)
    {
        $this->setProperties($code, $event, $status, $organization_id, $parent_id);
    }

    public function setProperties($code, $event, $status, $organization_id, $parent_id)
    {
        $this->code = $code;
        $this->data = json_encode($event->data);
        $this->parent = $parent_id;
        $this->event_id = $event->id ?? null;
        $this->organization_id = $organization_id;
        $this->errors = json_encode(data_get($event, 'errors'));
        $this->status =  $this->event_id !== null ? Event::STATUS_ERROR : $status;
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
