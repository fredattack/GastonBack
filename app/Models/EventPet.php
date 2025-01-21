<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

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
