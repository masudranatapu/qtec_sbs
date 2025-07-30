<?php

namespace App\Http\Controllers\Auth;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\AdminLoginRequest;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthController extends Controller
{
    public function login(AdminLoginRequest $request)
    {
        try {

            $admin = Admin::where('email', $request->email)->first();

            if (!$admin || !Hash::check($request->password, $admin->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'The provided credentials are incorrect.',
                    'data' =>  [],
                    'code' => Response::HTTP_UNAUTHORIZED
                ]);
            }

            $tokenResult = $admin->createToken('admin-token', ['admin']);

            $token = $tokenResult->plainTextToken;

            $expirationMinutes = config('sanctum.remember_me_expiration', 43200);
            $tokenResult->accessToken->expires_at = now()->addMinutes($expirationMinutes);

            $tokenResult->accessToken->save();

            return response()->json([
                'status' => true,
                'message' => 'Admin Successfully Logged In.',
                'data' => [
                    'token' => $token,
                    'admin' => $admin,
                    'expires_at' => $tokenResult->accessToken->expires_at,
                ],
                'code' => Response::HTTP_OK,
            ]);
        } catch (\Throwable $e) {
            return response()->json(
                false,
                $e->getMessage(),
                [],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function logout(Request $request)
    {
        try {

            $admin = $request->user('admin');

            if (!$admin) {
                return response()->json([
                    'status' => false,
                    'message' => 'No authenticated admin.',
                    'data' => [],
                    'code' => Response::HTTP_UNAUTHORIZED
                ]);
            }

            $admin->currentAccessToken()->delete();

            return response()->json([
                'status' =>  true,
                'message' => 'Admin successfully logged out.',
                'data' =>  [],
                'code' =>  Response::HTTP_OK
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Logout failed: ' . $e->getMessage(),
                'data' => [],
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR
            ]);
        }
    }
}
