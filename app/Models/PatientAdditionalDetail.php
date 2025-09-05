<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAdditionalDetail extends Model
{
    use HasFactory;

    protected $table = 'patient_additional_details';

    protected $fillable = [
        'guarantee_header_id',
        'case',
        'specific_date',
        'start_date',
        'end_date',
        'details',
        'definition',
        'amount',
        'price',
    ];

    protected $casts = [
        'specific_date' => 'array',
    ];

    public function header()
    {
        return $this->belongsTo(PatientAdditionalHeader::class, 'guarantee_header_id', 'id');
    }

    public function guaranteeCase()
    {
        return $this->belongsTo(GuaranteeAdditionalCase::class, 'case', 'case');
    }

    // Accessor to handle both single date and multiple dates
    public function getSpecificDatesAttribute()
    {
        if (is_string($this->specific_date)) {
            // Try to decode JSON, if it fails, return as single date array
            $decoded = json_decode($this->specific_date, true);
            return $decoded ?: [$this->specific_date];
        }
        
        return $this->specific_date ?: [];
    }
}
