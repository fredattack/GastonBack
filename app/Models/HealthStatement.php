<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $pet_id
 * @property string $type
 * @property string $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthStatement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthStatement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthStatement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthStatement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthStatement whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthStatement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthStatement wherePetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthStatement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthStatement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class HealthStatement extends Model
{
    //
}
