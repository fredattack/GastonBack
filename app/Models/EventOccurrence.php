<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $event_id
 * @property string $occurrence_date
 * @property int $is_done
 * @property int $is_deleted
 * @property string|null $custom_title
 * @property string|null $custom_notes
 * @property string|null $custom_start_time
 * @property string|null $custom_end_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\EventOccurrenceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventOccurrence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventOccurrence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventOccurrence query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventOccurrence whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventOccurrence whereCustomEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventOccurrence whereCustomNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventOccurrence whereCustomStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventOccurrence whereCustomTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventOccurrence whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventOccurrence whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventOccurrence whereIsDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventOccurrence whereIsDone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventOccurrence whereOccurrenceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventOccurrence whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EventOccurrence extends Model
{
    use HasFactory;
}
