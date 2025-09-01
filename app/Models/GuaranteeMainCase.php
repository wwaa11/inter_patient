<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuaranteeMainCase extends Model
{
    protected $fillable = [
        'case',
        'case_for_staff'
    ];
}