<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAdditionalHeader extends Model
{
    use HasFactory;

    protected $table = 'patient_additional_headers';

    protected $fillable = [
        'hn',
        'type',
        'embassy_ref',
        'mb',
        'issue_date',
        'cover_start_date',
        'cover_end_date',
        'total_price',
        'file',
    ];

    protected $casts = [
        'cover_start_date' => 'date',
        'cover_end_date'   => 'date',
        'file'             => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'hn', 'hn');
    }

    public function additionalType()
    {
        return $this->hasOne(PatientAdditionalType::class, 'id', 'type');
    }

    public function details()
    {
        return $this->hasMany(PatientAdditionalDetail::class, 'guarantee_header_id', 'id');
    }

    public function coverPeriod()
    {
        if ($this->cover_start_date && $this->cover_end_date) {
            return date('d/m/Y', strtotime($this->cover_start_date)) . ' - ' . date('d/m/Y', strtotime($this->cover_end_date));
        } else {
            return 'N/A';
        }
    }

    public function issueDate()
    {
        return date('d/m/Y', strtotime($this->issue_date));
    }

    public function isInCoverPeriod()
    {
        $today      = now();
        $coverStart = \Carbon\Carbon::parse($this->cover_start_date);
        $coverEnd   = \Carbon\Carbon::parse($this->cover_end_date);
        if ($coverStart && $coverEnd) {
            return $today->between($coverStart, $coverEnd);
        } else {
            return 'N/A';
        }
    }
}
