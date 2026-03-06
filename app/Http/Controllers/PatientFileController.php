<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PatientFileController extends Controller
{
    public function view(string $hn, string $filename): BinaryFileResponse
    {
        $filePath = public_path('hn/'.$hn.'/'.$filename);

        if (! file_exists($filePath)) {
            abort(404, 'File not found');
        }

        $mimeType = mime_content_type($filePath);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }
}
