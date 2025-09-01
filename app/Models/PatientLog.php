<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientLog extends Model
{
    protected $fillable = [
        'hn',
        'action',
        'action_by'
    ];

    /**
     * Get the patient that owns the log.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'hn', 'hn');
    }

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'action_by', 'userid');
    }
}
