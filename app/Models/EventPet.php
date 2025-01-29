<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * 
 *
 * @property int $id
 * @property int $event_id
 * @property int $pet_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $detail_type
 * @property string|null $item
 * @property string|null $quantity
 * @property string|null $notes
 * @property-read \App\Models\Event $event
 * @property-read \App\Models\Pet $pet
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPet whereDetailType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPet whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPet whereItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPet whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPet wherePetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPet whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPet whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EventPet extends Pivot
{
    protected $table = 'event_pet';

    public function event(): BelongsTo
    {
        return $this->belongsTo( Event::class );
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo( Pet::class );
    }
}
