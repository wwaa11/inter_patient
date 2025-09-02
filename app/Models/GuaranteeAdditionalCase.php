<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuaranteeAdditionalCase extends Model
{
    use HasFactory;

    protected $table = 'guarantee_addtional_cases';

    protected $fillable = [
        'case',
        'definition',
    ];

    public function additionalDetails()
    {
        return $this->hasMany(PatientAdditionalDetail::class, 'case', 'case');
    }
}
