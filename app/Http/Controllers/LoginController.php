<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function Login()
    {

        return view('auth.login');
    }

    public function LoginRequest(Request $req)
    {
        $userid   = $req->userid;
        $password = $req->password;
        $data     = [
            'status'  => 'failed',
            'message' => null,
        ];

        if (env('APP_ENV') == 'local') {
            $data['status']  = 'success';
            $data['message'] = 'เข้าสู่ระบบสำเร็จ';

            $userData = User::where('userid', $userid)->first();
            if ($userData == null) {
                $response = Http::withHeaders(['token' => env('API_AUTH_KEY')])
                    ->timeout(30)
                    ->post('http://172.20.1.12/dbstaff/api/getuser', [
                        'userid' => $req->userid,
                    ]);
                if ($response->successful()) {
                    $responseData = $response->json();
                    if ($responseData['status'] == 1) {
                        $userData             = new User();
                        $userData->userid     = $userid;
                        $userData->name       = $responseData['user']['name'];
                        $userData->position   = $responseData['user']['position'];
                        $userData->department = $responseData['user']['department'];
                        $userData->division   = $responseData['user']['division'];
                        $userData->role       = 'user';
                        $userData->save();
                    }
                }
            }
            Auth::login($userData);

            return response()->json($data, 200);
        }

        try {
            $response = Http::withHeaders(['token' => env('API_AUTH_KEY')])
                ->timeout(30)
                ->post('http://172.20.1.12/dbstaff/api/auth', [
                    'userid'   => $req->userid,
                    'password' => $req->password,
                ]);

            // Check if the HTTP request was successful
            if (! $response->successful()) {
                $data['message'] = 'ไม่สามารถเชื่อมต่อกับระบบได้ กรุณาลองใหม่อีกครั้ง';
                return response()->json($data, 200);
            }

            $responseData = $response->json();

            // Check if response has required structure
            if (! isset($responseData['status'])) {
                $data['message'] = 'ข้อมูลที่ได้รับจากระบบไม่ถูกต้อง';
                return response()->json($data, 200);
            }

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Login API Error: ' . $e->getMessage(), [
                'userid' => $req->userid,
                'error'  => $e->getMessage(),
            ]);

            $data['message'] = 'เกิดข้อผิดพลาดในการเชื่อมต่อ กรุณาลองใหม่อีกครั้ง';
            return response()->json($data, 200);
        }

        $data['message'] = 'ไม่พบรหัสพนักงานนี้';

        if ($responseData['status'] == 1) {
            $userData = User::where('userid', $req->userid)->first();
            if (! $userData) {
                $userData         = new User();
                $userData->userid = $userid;
                $userData->role   = 'user';
            }
            $userData->name       = $responseData['user']['name'];
            $userData->position   = $responseData['user']['position'];
            $userData->department = $responseData['user']['department'];
            $userData->division   = $responseData['user']['division'];
            $userData->save();

            Auth::login($userData);

            $data['status']  = 'success';
            $data['message'] = 'เข้าสู่ระบบสำเร็จ';
        }

        return response()->json($data, 200);
    }

    public function LogoutRequest(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
