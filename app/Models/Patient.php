<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $primaryKey = 'hn';
    public $incrementing  = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'hn',
        'name',
        'gender',
        'birthday',
        'qid',
        'nationality',
        'type',
        'location',
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    // Relationships
    public function notes()
    {
        return $this->hasMany(PatientNote::class, 'hn', 'hn')->orderBy('created_at', 'desc');
    }

    public function passports()
    {
        return $this->hasMany(PatientPassport::class, 'hn', 'hn');
    }

    public function medicalReports()
    {
        return $this->hasMany(PatientMedicalReport::class, 'hn', 'hn');
    }

    public function guaranteeMains()
    {
        return $this->hasMany(PatientMainGuarantee::class, 'hn', 'hn');
    }

    public function guaranteeAdditionals()
    {
        // return $this->hasMany(PatientAdditionalHeader::class, 'hn', 'hn');
        return $this->hasMany(PatientAdditionalHeader::class, 'hn', 'hn')
            ->orderByRaw('CASE WHEN CAST(GETDATE() AS DATE) BETWEEN cover_start_date AND cover_end_date THEN 1 ELSE 0 END DESC');
    }
}
