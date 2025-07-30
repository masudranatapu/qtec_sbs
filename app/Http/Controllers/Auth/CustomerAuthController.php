<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Customer\CustomerLoginRequest;
use App\Http\Requests\Customer\CustomerRegisterRequest;

class CustomerAuthController extends Controller
{

    public function login(CustomerLoginRequest $request)
    {
        try {

            $customer = User::where('email', $request->email)->first();

            if (!$customer || !Hash::check($request->password, $customer->password)) {

                return response()->json([
                    'status' => false,
                    'message' => 'The provided credentials are incorrect.',
                    'data' => [],
                    'code' => Response::HTTP_NO_CONTENT
                ]);
            }

            $tokenResult = $customer->createToken('customer-token', ['customer']);

            $token = $tokenResult->plainTextToken;

            $expirationMinutes = config('sanctum.remember_me_expiration', 43200);
            $tokenResult->accessToken->expires_at = now()->addMinutes($expirationMinutes);

            $tokenResult->accessToken->save();

            return response()->json([
                'status' => true,
                'message' => 'Customer Successfully Logged In.',
                'data' => [
                    'token' => $token,
                    'customer' => $customer,
                    'expires_at' => $tokenResult->accessToken->expires_at,
                ],
                'code' => Response::HTTP_OK,
            ]);
        } catch (\Throwable $e) {

            return response()->json(
                false,
                $e->getMessage(),
                [],
                Response::HTTP_NO_CONTENT
            );
        }
    }

    public function register(CustomerRegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            $customer = new User();
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->password = Hash::make($request->password);
            $customer->save();

            DB::commit();

            return response()->json([
                'status' =>  true,
                'message' =>  'Customer successfully registered.',
                'data' => [],
                'code' =>  Response::HTTP_OK
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' =>  false,
                'message' =>  $e->getMessage(),
                'data' =>  [],
                'code' =>  Response::HTTP_INTERNAL_SERVER_ERROR
            ]);
        }
    }

    public function customerLogout(Request $request)
    {
        try {
            $customer = $request->user('customer');

            if (!$customer) {
                return response()->json([
                    'status' => false,
                    'message' => 'No authenticated customer.',
                    'data' => [],
                    'code' => Response::HTTP_UNAUTHORIZED
                ]);
            }

            $customer->currentAccessToken()->delete();

            return response()->json([
                'status' =>  true,
                'message' =>  'Customer successfully logged out.',
                'data' => [],
                'code' =>  Response::HTTP_OK
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' =>  false,
                'message' =>  $e->getMessage(),
                'data' =>  [],
                'code' =>  Response::HTTP_INTERNAL_SERVER_ERROR
            ]);
        }
    }
}
