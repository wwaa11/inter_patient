<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notifier extends Model
{
    protected $fillable = ['name'];

    public function preAuthorizations(): HasMany
    {
        return $this->hasMany(PreAuthorization::class);
    }
}
