<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientMedicalReport extends Model
{
    use HasFactory;

    protected $table = 'patient_medical_reports';

    protected $fillable = [
        'hn',
        'date',
        'file',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'hn', 'hn');
    }
}
