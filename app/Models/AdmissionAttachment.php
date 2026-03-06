<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionAttachment extends Model
{
    protected $fillable = ['admission_id', 'path', 'original_name'];

    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }
}
