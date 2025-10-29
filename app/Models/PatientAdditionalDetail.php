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
        'use_date',
        'details',
        'definition',
        'amount',
        'price',
    ];

    protected $casts = [
        'specific_date' => 'array',
        'use_date'      => 'date',
        'price'         => 'decimal:2',
    ];

    public function header()
    {
        return $this->belongsTo(PatientAdditionalHeader::class, 'guarantee_header_id', 'id');
    }

    public function guaranteeCase()
    {
        return $this->hasOne(GuaranteeCase::class, 'id', 'case');
    }

    public function specificDate()
    {
        if ($this->start_date && $this->end_date) {
            $isValid     = $this->isDateRangeValid();
            $statusClass = $isValid ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
            $icon        = $isValid ? 'fas fa-check-circle' : 'fas fa-times-circle';

            return '<span class="' . $statusClass . '"><i class="' . $icon . ' text-xs"></i></span> ' . date('d/m/Y', strtotime($this->start_date)) . ' - ' . date('d/m/Y', strtotime($this->end_date));
        } else if ($this->specific_date !== []) {
            $data = '';
            foreach ($this->specific_date as $date) {
                $isValid     = $this->isSpecificDateValid($date);
                $statusClass = $isValid ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
                $icon        = $isValid ? 'fas fa-check-circle' : 'fas fa-times-circle';
                $data .= '<div class="flex items-center space-x-2 mb-1">';
                $data .= '<span class="' . $statusClass . '"><i class="' . $icon . ' text-xs"></i></span>';
                $data .= '<span>' . date('d/m/Y', strtotime($date)) . '</span>';
                $data .= '</div>';
            }
            return $data;
        } else {
            return 'N/A';
        }
    }

    public function isSpecificDateValid($date = null)
    {
        $today = now();

        if ($date) {
            $specificDate = \Carbon\Carbon::parse($date);
            return $today->isSameDay($specificDate) || $today->lessThan($specificDate);
        }

        return false;
    }

    public function isDateRangeValid()
    {
        if ($this->start_date && $this->end_date) {
            $today     = now();
            $startDate = \Carbon\Carbon::parse($this->start_date);
            $endDate   = \Carbon\Carbon::parse($this->end_date);
            return $today->between($startDate, $endDate);
        }

        return false;
    }

    public function hasValidDates()
    {
        // Check date range validity
        if ($this->start_date && $this->end_date) {
            return $this->isDateRangeValid();
        }

        // Check specific dates validity
        if ($this->specific_date && is_array($this->specific_date) && count($this->specific_date) > 0) {
            foreach ($this->specific_date as $date) {
                if ($this->isSpecificDateValid($date)) {
                    return true;
                }
            }
        }

        return false;
    }
}
