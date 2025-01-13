<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $local
 * @property string|null $street
 * @property string|null $phone
 * @property string|null $country
 * @property string|null $city
 * @property string|null $zip
 * @property string|null $email
 * @property int|null $master_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\FamillyFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Familly newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Familly newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Familly query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Familly whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Familly whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Familly whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Familly whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Familly whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Familly whereLocal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Familly whereMasterUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Familly whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Familly wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Familly whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Familly whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Familly whereZip($value)
 * @mixin \Eloquent
 */
class Familly extends Model
{
    use hasFactory;
    //
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
