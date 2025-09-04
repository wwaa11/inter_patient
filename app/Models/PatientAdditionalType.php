<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAdditionalType extends Model
{
    use HasFactory;

    protected $table = 'patient_additional_types';

    protected $fillable = [
        'name',
        'colour',
    ];

    public function additionalHeaders()
    {
        return $this->hasMany(PatientAdditionalHeader::class, 'type', 'name');
    }
}
