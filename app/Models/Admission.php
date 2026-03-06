<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admission extends Model
{
    use SoftDeletes;

    public const DEPARTMENT_WARDS = 'Wards';

    public const DEPARTMENT_ADMISSION = 'Admission';

    public const DEPARTMENT_OPD_CLINICS = 'OPD Clinics';

    public const DEPARTMENT_OPD_IPD_CASHIERS = 'OPD/IPD Cashiers';

    public const DEPARTMENT_EMERGENCY = 'Emergency Department';

    public const ADMITTING_STATUS_ADMITTED = 'Admitted';

    public const ADMITTING_STATUS_DISCHARGED = 'Discharged';

    public const ADMITTING_STATUS_PLAN_DISCHARGED = 'Plan Discharged';

    public const CASE_STATUS_OPENING = 'Opening';

    public const CASE_STATUS_CLOSE = 'Close';

    public const CASE_STATUS_DELETED = 'Deleted';

    public const GOP_STATUS_COVERED = 'Covered';

    public const GOP_STATUS_NOT_COVERED = 'Not Covered';

    public const GOP_STATUS_PARTIALLY_COVERED = 'Partially Covered';

    public const GOP_STATUS_PAY_AND_CLAIM = 'Pay and Claim';

    protected $fillable = [
        'hn',
        'name',
        'admission_date',
        'room_no',
        'diagnosis',
        'procedure_treatment',
        'pre_authorization_id',
        'additional_note',
        'department',
        'admitting_status',
        'case_status',
        'sent_out_date',
        'initial_gop_receiving_date',
        'gop_pre_certification_status',
        'gop_ref',
        'discharge_date',
        'final_gop',
    ];

    protected function casts(): array
    {
        return [
            'admission_date' => 'date',
            'sent_out_date' => 'date',
            'initial_gop_receiving_date' => 'date',
            'discharge_date' => 'date',
            'final_gop' => 'datetime',
        ];
    }

    public function preAuthorization(): BelongsTo
    {
        return $this->belongsTo(PreAuthorization::class);
    }

    public function contactProviders(): BelongsToMany
    {
        return $this->belongsToMany(Provider::class, 'admission_provider')->withTimestamps();
    }

    public function handlingUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'admission_handling_user')->withTimestamps();
    }

    public function gopTranslators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'admission_gop_translator')->withTimestamps();
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(AdmissionAttachment::class);
    }

    public static function departmentOptions(): array
    {
        return [
            self::DEPARTMENT_WARDS,
            self::DEPARTMENT_ADMISSION,
            self::DEPARTMENT_OPD_CLINICS,
            self::DEPARTMENT_OPD_IPD_CASHIERS,
            self::DEPARTMENT_EMERGENCY,
        ];
    }

    public static function admittingStatusOptions(): array
    {
        return [
            self::ADMITTING_STATUS_ADMITTED,
            self::ADMITTING_STATUS_DISCHARGED,
            self::ADMITTING_STATUS_PLAN_DISCHARGED,
        ];
    }

    public static function caseStatusOptions(): array
    {
        return [
            self::CASE_STATUS_OPENING,
            self::CASE_STATUS_CLOSE,
            self::CASE_STATUS_DELETED,
        ];
    }

    public static function gopPreCertificationStatusOptions(): array
    {
        return [
            self::GOP_STATUS_COVERED,
            self::GOP_STATUS_NOT_COVERED,
            self::GOP_STATUS_PARTIALLY_COVERED,
            self::GOP_STATUS_PAY_AND_CLAIM,
        ];
    }
}
