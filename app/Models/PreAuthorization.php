<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreAuthorization extends Model
{
    use SoftDeletes;

    public const CASE_STATUS_APPEL = 'Appel';

    public const CASE_STATUS_COMPLETE = 'Complete';

    public const CASE_STATUS_DATA_ENTERED = 'Data Entered';

    public const CASE_STATUS_IN_PROGRESS = 'In-Progress';

    public const CASE_STATUS_PENDED_MR_MQ = 'Pended - MR MQ';

    public const CASE_STATUS_AWAITING_PATIENT_NOTIFICATION = 'Awaiting Patient Notification';

    public const CASE_STATUS_DELETED = 'Deleted';

    public const COVERAGE_COVERED = 'Covered';

    public const COVERAGE_NOT_COVERED = 'Not Covered';

    public const COVERAGE_PARTIALLY_COVERED = 'Partially Covered';

    protected $fillable = [
        'service_type_id',
        'provider_id',
        'hn',
        'patient_name',
        'date_of_service',
        'operations_procedures',
        'notifier_id',
        'requested_date',
        'case_status',
        'coverage_decision',
        'send_out_date',
        'gop_receiving_date',
        'gop_reference_number',
        'gop_translate_by',
    ];

    protected function casts(): array
    {
        return [
            'date_of_service' => 'date',
            'requested_date' => 'datetime',
            'send_out_date' => 'date',
            'gop_receiving_date' => 'date',
        ];
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function notifier(): BelongsTo
    {
        return $this->belongsTo(Notifier::class);
    }

    public function handlingStaffs(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'pre_authorization_handling_staff')
            ->withTimestamps();
    }

    public function gopTranslateByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gop_translate_by');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(PreAuthorizationAttachment::class);
    }

    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class);
    }

    public static function caseStatusOptions(): array
    {
        return [
            self::CASE_STATUS_APPEL,
            self::CASE_STATUS_COMPLETE,
            self::CASE_STATUS_DATA_ENTERED,
            self::CASE_STATUS_IN_PROGRESS,
            self::CASE_STATUS_PENDED_MR_MQ,
            self::CASE_STATUS_AWAITING_PATIENT_NOTIFICATION,
            self::CASE_STATUS_DELETED,
        ];
    }

    public static function coverageDecisionOptions(): array
    {
        return [
            self::COVERAGE_COVERED,
            self::COVERAGE_NOT_COVERED,
            self::COVERAGE_PARTIALLY_COVERED,
        ];
    }
}
