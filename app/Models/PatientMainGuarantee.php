<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientMainGuarantee extends Model
{
    use HasFactory;

    protected $table = 'patient_main_guarantees';

    protected $fillable = [
        'hn',
        'embassy',
        'embassy_ref',
        'number',
        'mb',
        'issue_date',
        'cover_start_date',
        'cover_end_date',
        'case',
        'file',
        'extension',
        'extension_cover_end_date',
    ];

    protected $casts = [
        'file'                     => 'array',
        'cover_start_date'         => 'date',
        'cover_end_date'           => 'date',
        'extension'                => 'boolean',
        'extension_cover_end_date' => 'date',
    ];

    public function issueDate()
    {
        return date('d/m/Y', strtotime($this->issue_date));
    }

    public function coverPeriod()
    {
        return date('d/m/Y', strtotime($this->cover_start_date)) . ' - ' . date('d/m/Y', strtotime($this->cover_end_date));
    }

    public function extensionCoverEndDate()
    {
        if ($this->extension) {
            return date('d/m/Y', strtotime($this->extension_cover_end_date));
        }

        return null;
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'hn', 'hn');
    }

    public function guaranteeCase()
    {
        return $this->hasOne(GuaranteeCase::class, 'id', 'case');
    }

    public function embassyData()
    {
        return $this->hasOne(Embassy::class, 'name', 'embassy');
    }
}
