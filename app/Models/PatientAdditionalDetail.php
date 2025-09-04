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
        'details',
        'definition',
        'amount',
        'price',
    ];

    public function header()
    {
        return $this->belongsTo(PatientAdditionalHeader::class, 'guarantee_header_id', 'id');
    }

    public function guaranteeCase()
    {
        return $this->belongsTo(GuaranteeAdditionalCase::class, 'case', 'case');
    }
}
