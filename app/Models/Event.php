<?php

namespace App\Models;

use App\Filters\EventFilterPipeline;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property string $type
 * @property string $title
 * @property int $is_full_day
 * @property string $start_date
 * @property string|null $end_date
 * @property int $is_recurring
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Recurrence|null $recurrence
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereIsFullDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereIsRecurring($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedAt($value)
 * @property int $is_done
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventOccurrence> $occurrences
 * @property-read int|null $occurrences_count
 * @property-read \App\Models\EventPet|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pet> $pets
 * @property-read int|null $pets_count
 * @method static \Database\Factories\EventFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereIsDone($value)
 * @mixin \Eloquent
 */
class Event extends Model
{
    use HasFactory;

    public const EXCEPTED_UPDATE_FIELDS = ['pet_id', 'petId', 'recurrence', 'pets', 'master_id'];

    protected $casts = [
        'is_full_day' => 'boolean',
        'is_recurring' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function recurrence() {
        return $this->hasOne(Recurrence::class);
    }

    public function occurrences()
    {
        return $this->hasMany(EventOccurrence::class);
    }

    public function pets() {
        return $this->belongsToMany(Pet::class)
            ->using(EventPet::class)
            ->withPivot(['detail_type', 'item', 'quantity', 'notes'])
            ->withTimestamps();;
    }

    public static function filter()
    {
        return app(EventFilterPipeline::class, ['filters' => request()->all()['filters'] ?? []])
            ->send(self::query())
            ->thenReturn();
    }
}
