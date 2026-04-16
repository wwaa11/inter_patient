<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreAuthorizationNote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'pre_authorization_id',
        'note',
        'created_by',
    ];

    public function preAuthorization(): BelongsTo
    {
        return $this->belongsTo(PreAuthorization::class);
    }
}
