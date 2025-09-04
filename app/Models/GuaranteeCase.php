<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuaranteeCase extends Model
{
    protected $table = 'guarantee_cases';

    protected $fillable = [
        'name',
        'definition',
        'colour',
    ];
}
