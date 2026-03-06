<?php

namespace App\Services;

use App\Models\PatientLog;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class PatientService
{
    /**
     * Log a patient action.
     */
    public function logAction(string $hn, string $action): void
    {
        PatientLog::create([
            'hn' => $hn,
            'action' => $action.' ('.auth()->user()->name.')',
            'action_by' => auth()->user()->userid,
        ]);
    }

    /**
     * Handle file upload for a patient.
     */
    public function uploadFile(string $hn, UploadedFile $file, string $prefix): string
    {
        $directory = public_path('hn/'.$hn);

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0777, true);
        }

        $filename = $prefix.'_'.time().'_'.$file->getClientOriginalName();
        $file->move($directory, $filename);

        return $filename;
    }

    /**
     * Delete a patient file.
     */
    public function deleteFile(string $hn, string $filename): bool
    {
        $filePath = public_path('hn/'.$hn.'/'.$filename);

        if (File::exists($filePath)) {
            return File::delete($filePath);
        }

        return false;
    }

    /**
     * Calculate status and classes for main guarantees.
     */
    public function calculateGuaranteeStatus($guarantee): void
    {
        $endDate = \Carbon\Carbon::parse($guarantee->extension_cover_end_date ?? $guarantee->cover_end_date);
        $now = \Carbon\Carbon::now();
        $daysUntilExpiry = $now->diff($endDate, false);

        if ($daysUntilExpiry->days > 0 && $daysUntilExpiry->invert == 0) {
            $guarantee->status_class = 'bg-green-100 text-green-800';
            $guarantee->status_text = 'Valid';
        } elseif ($daysUntilExpiry->days == 0 && $daysUntilExpiry->invert == 1) {
            $guarantee->status_class = 'bg-green-100 text-green-800';
            $guarantee->status_text = 'Valid';
        } else {
            $guarantee->status_class = 'bg-red-100 text-red-800';
            $guarantee->status_text = 'Invalid';
        }
    }

    /**
     * Calculate status and classes for passports.
     */
    public function calculatePassportStatus($passport): void
    {
        $expiryDate = \Carbon\Carbon::parse($passport->expiry_date);
        $now = \Carbon\Carbon::now();
        $daysUntilExpiry = $now->diff($expiryDate, false);

        if ($daysUntilExpiry->days > 0 && $daysUntilExpiry->invert == 0) {
            $passport->status = 'valid';
            $passport->status_class = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
            $passport->status_text = 'Valid';
        } elseif ($daysUntilExpiry->days == 0 && $daysUntilExpiry->invert == 1) {
            $passport->status = 'expiring_soon';
            $passport->status_class = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
            $passport->status_text = 'Expiring Soon';
        } elseif ($daysUntilExpiry->days <= 90 && $daysUntilExpiry->invert == 1) {
            $passport->status = 'expiring_soon';
            $passport->status_class = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
            $passport->status_text = 'Expiring Soon';
        } else {
            $passport->status = 'expired';
            $passport->status_class = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
            $passport->status_text = 'Expired';
        }
    }
}
