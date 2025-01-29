<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int $event_id
 * @property string $frequencyType
 * @property int $frequency
 * @property string|null $days
 * @property string|null $endDate
 * @property int|null $occurrences
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recurrence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recurrence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recurrence query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recurrence whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recurrence whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recurrence whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recurrence whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recurrence whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recurrence whereFrequencyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recurrence whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recurrence whereOccurrences($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recurrence whereUpdatedAt($value)
 * @property string $frequency_type
 * @property string|null $end_date
 * @mixin \Eloquent
 */
class Recurrence extends Model
{
    use HasFactory;
    protected $casts = [
        'days' => 'array',
    ];

    public function event() {
        return $this->belongsTo(Event::class);
    }
}
