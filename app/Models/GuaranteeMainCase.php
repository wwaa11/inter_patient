<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuaranteeMainCase extends Model
{
    protected $table = 'guarantee_main_cases';

    protected $fillable = [
        'case',
        'definition',
    ];
}
