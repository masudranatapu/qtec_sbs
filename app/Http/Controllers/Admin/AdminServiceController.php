<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Booking;
use Symfony\Component\HttpFoundation\Response;

class AdminServiceController extends Controller
{
    public function bookingList()
    {
        try {
            $bookings = Booking::query()
                ->with([
                    'customer' => fn($q) => $q->select(['id', 'name', 'email']),
                    'service' => fn($q) => $q->select(['id', 'name', 'price']),
                ])
                ->get();

            return response()->json([
                'status' =>  true,
                'message' => 'Data fetched successfully.',
                'data' => $bookings,
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

    public function index()
    {
        try {
            $services = Service::query()
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

    public function store(ServiceRequest $request)
    {
        try {

            DB::beginTransaction();

            $service = new Service();
            $service->name = $request->name;
            $service->description = $request->description;
            $service->price = $request->price;
            $service->status = $request->status;
            $service->save();

            DB::commit();

            return response()->json([
                'status' =>  true,
                'message' => 'Data created successfully.',
                'data' => $service,
                'code' =>  Response::HTTP_OK
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status' =>  false,
                'message' =>  $e->getMessage(),
                'data' =>  [],
                'code' =>  Response::HTTP_INTERNAL_SERVER_ERROR
            ]);
        }
    }

    public function update(ServiceRequest $request, $id)
    {
        try {

            DB::beginTransaction();

            $service = Service::find($id);

            if (!$service) {
                return response()->json([
                    'status' =>  false,
                    'message' =>  'Data not found.',
                    'data' =>  [],
                    'code' =>  Response::HTTP_NOT_FOUND
                ]);
            }

            $service->name = $request->name;
            $service->description = $request->description;
            $service->price = $request->price;
            $service->status = $request->status;
            $service->save();

            DB::commit();

            return response()->json([
                'status' =>  true,
                'message' => 'Data updated successfully.',
                'data' => $service->refresh(),
                'code' =>  Response::HTTP_OK
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status' =>  false,
                'message' =>  $e->getMessage(),
                'data' =>  [],
                'code' =>  Response::HTTP_INTERNAL_SERVER_ERROR
            ]);
        }
    }

    public function delete($id)
    {
        try {

            DB::beginTransaction();

            $service = Service::find($id);

            if (!$service) {
                return response()->json([
                    'status' =>  false,
                    'message' =>  'Data not found.',
                    'data' =>  [],
                    'code' =>  Response::HTTP_NOT_FOUND
                ]);
            }

            $service->delete();

            DB::commit();

            return response()->json([
                'status' =>  true,
                'message' => 'Data deleted successfully.',
                'data' => [],
                'code' =>  Response::HTTP_OK
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status' =>  false,
                'message' =>  $e->getMessage(),
                'data' =>  [],
                'code' =>  Response::HTTP_INTERNAL_SERVER_ERROR
            ]);
        }
    }
}
