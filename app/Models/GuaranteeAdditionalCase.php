<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuaranteeAdditionalCase extends Model
{
    use HasFactory;

    protected $table = 'guarantee_additional_cases';

    protected $fillable = [
        'name',
        'definition',
        'colour',
    ];

    public function additionalDetails()
    {
        return $this->hasMany(PatientAdditionalDetail::class, 'case', 'name');
    }
}
