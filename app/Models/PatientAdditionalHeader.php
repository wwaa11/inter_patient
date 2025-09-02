<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAdditionalHeader extends Model
{
    use HasFactory;

    protected $table = 'patient_addtional_headers';

    protected $fillable = [
        'hn',
        'type',
        'embassy_ref',
        'mb',
        'issue_date',
        'cover_start_date',
        'cover_end_date',
        'total_price',
        'file'
    ];

    protected $casts = [
        'cover_start_date' => 'date',
        'cover_end_date' => 'date',
        'file' => 'array'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'hn', 'hn');
    }

    public function additionalType()
    {
        return $this->belongsTo(PatientAdditionalType::class, 'type', 'type');
    }

    public function details()
    {
        return $this->hasMany(PatientAdditionalDetail::class, 'guarantee_header_id', 'id');
    }
}