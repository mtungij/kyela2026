<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
        'business_address',
        'amount',
        'type',
        'number_type'   
        ,     'penalty_per_day',    ];

    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalCollectionAmountAttribute()
    {
        return $this->amount * $this->number_type;
    }
}
