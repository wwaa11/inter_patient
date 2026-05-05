<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PatientApiController extends Controller
{
    public function getPatientInfo(Request $request): JsonResponse
    {
        $hn = $request->hn;

        try {
            $response = Http::withoutVerifying()->withHeaders(['Authorization' => 'Bearer '.env('API_HIS_PATIENT_KEY')])
                ->timeout(30)
                ->post('https://patienthistory.int-app.praram9.com/patienthistory/api/patient-info', [
                    'hn' => $hn,
                ]);

            if (! $response->successful()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to get patient info from external API',
                ], 500);
            }

            $patientData = $response->json();
            if ($patientData['status'] == 'success') {
                $patientData = $patientData['patient'];
                $firstName = $patientData['firstname'];
                $lastName = $patientData['lastname'];

                $patientData = [
                    'name' => $firstName.' '.$lastName,
                    'gender' => $patientData['gender'],
                    'birthday' => date('Y-m-d', strtotime($patientData['birthdate'])),
                    'nationality' => $patientData['nationality'],
                ];

                return response()->json([
                    'status' => 'success',
                    'message' => 'Patient data retrieved successfully',
                    'data' => $patientData,
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Patient not found',
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: '.$e->getMessage(),
            ], 500);
        }
    }
}
