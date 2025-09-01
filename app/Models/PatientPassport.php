<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientPassport extends Model
{
    use HasFactory;

    protected $fillable = [
        'hn',
        'file',
        'number',
        'issue_date',
        'expiry_date',
    ];

    protected $casts = [
        'issue_date'  => 'date',
        'expiry_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'hn', 'hn');
    }
}
