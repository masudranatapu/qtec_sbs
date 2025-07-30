<?php

namespace App\Http\Controllers\Customer;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\BookingRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{

    public function serviceList()
    {
        try {
            $services = Service::query()
                ->where('status', 'Active')
                ->get();

            return response()->json([
                'status' =>  true,
                'message' => 'Data fetched successfully.',
                'data' => $services,
                'code' =>  Response::HTTP_OK
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' =>  false,
                'message' =>  $e->getMessage(),
                'data' =>  [],
                'code' =>  Response::HTTP_INTERNAL_SERVER_ERROR
            ]);
        }
    }

    public function bookings(BookingRequest $request)
    {
        try {
            $user = Auth::guard('customer')->user();

            DB::beginTransaction();

            $alreadyTaken = Booking::query()
                ->where('service_id', $request->service_id)
                ->where('booking_date', $request->booking_date)
                ->where('user_id', '!=', $user->id)
                ->exists();

            if ($alreadyTaken) {
                return response()->json([
                    'status' => false,
                    'message' => 'This service has already been booked by another user on the selected date.',
                    'data' => [],
                    'code' => Response::HTTP_CONFLICT
                ]);
            }

            $alreadyBookedByUser = Booking::query()
                ->where('service_id', $request->service_id)
                ->where('booking_date', $request->booking_date)
                ->where('user_id', $user->id)
                ->exists();

            if ($alreadyBookedByUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'You have already booked this service on the selected date.',
                    'data' => [],
                    'code' => Response::HTTP_CONFLICT
                ]);
            }

            $booking = new Booking();
            $booking->user_id = $user->id;
            $booking->service_id = $request->service_id;
            $booking->booking_date = $request->booking_date;
            $booking->status = 'Pending';
            $booking->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Service booked successfully.',
                'data' => $booking,
                'code' => Response::HTTP_OK
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [],
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR
            ]);
        }
    }
}
