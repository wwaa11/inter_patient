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
            $response = Http::withHeaders(['key' => config('services.ssb.key')])
                ->timeout(30)
                ->post('http://172.20.1.22/w_phr/api/patient/info', [
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
                $firstName = ($patientData['name']['first_en'] != '') ? $patientData['name']['first_en'] : $patientData['name']['first_th'];
                $lastName = ($patientData['name']['last_en'] != '') ? $patientData['name']['last_en'] : $patientData['name']['last_th'];
                $patientData = [
                    'name' => $firstName.' '.$lastName,
                    'gender' => ($patientData['gender'] == 'ชาย') ? 'Male' : 'Female',
                    'birthday' => date('Y-m-d', strtotime($patientData['brithdate'])),
                    'nationality' => ($patientData['national'] == 'ไทย') ? 'Thai' : $patientData['national'],
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
