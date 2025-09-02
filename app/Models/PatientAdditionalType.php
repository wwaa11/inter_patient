<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAdditionalType extends Model
{
    use HasFactory;

    protected $table = 'patient_addtional_types';

    protected $fillable = [
        'type'
    ];

    public function additionalHeaders()
    {
        return $this->hasMany(PatientAdditionalHeader::class, 'type', 'type');
    }
}