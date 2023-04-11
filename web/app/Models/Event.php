<?php

namespace App\Models;

use App\Models\RelationTraits\RelateToOrganization;
use Illuminate\Database\Eloquent\Model;

/**
 * @property numeric $code - Код события в соответствии с документацией сервиса Калуги
 * @property string $parent - Идентификатор родительского события
 * @property string $data - json, содержащий данные для текущего события
 * @property string $event_id - Идентификатор в очереди сервиса Калуга
 * @property integer $status - Event status
 * @property integer $organization_id
 * @property Organization $organization
 */
class Event extends Model
{
    use RelateToOrganization;
    const STATUS_ERROR = -1;
    const STATUS_WAITING = 0;
    const STATUS_SUCCESS = 1;

    protected $fillable = [
        'id',
        'code',
        'parent',
        'data',
        'event_id',
        'errors',
        'user_id',
        'status',
    ];

    public function events()
    {
        return $this->hasMany(Event::class, 'parent', 'event_id');
    }

    public function parentEvent()
    {
        return $this->belongsTo(Event::class, 'parent', 'event_id');
    }
}
