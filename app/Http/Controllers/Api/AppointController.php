<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\AppointCreate;
use App\Mail\StaffCreate;
use App\Models\Appoint;
use App\Models\Service;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Support\Facades\Mail;
use Str;

class AppointController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'adminRoleChecker'], ['only' => ['index']]);
        $this->middleware(['auth:sanctum'], ['only' => ['userAppoints']]);
    }

    public function index()
    {
        $appoints = Appoint::with(['service', 'staff', 'user'])->get();

        if ($appoints !== null) {
            return response([
                'status' => 'done',
                'appoints' => $appoints
            ], 200);
        } else {
            return response([
                'status' => 'error',
                'message' => 'No appoints found...'
            ], 200);
        }
    }

    public function store()
    {
        $service = Service::where('id', request('service_id'))->first();
        $staff = User::where('role_id', 2)->where('id', request('staff_id'))->first();
        $user = null;

        if (request('user')['email']) {
            $user = User::where('role_id', 3)->where('email', request('user')['email'])->first();

            if ($user === null) {
                $dummyPassword = Str::random(16);

                $user = new User();
                $user->role_id = 3;
                $user->name = request('user')['name'];
                $user->email = request('user')['email'];
                $user->phone = request('user')['phone'];
                $user->description = request('user')['description'];
                $user->address = request('user')['address'];
                $user->password = Hash::make($dummyPassword);
                $user->save();

                $subject = 'New User Create';

                Mail::to($user->email)->send(new StaffCreate($user, $dummyPassword, $subject));
            }
        }

        if ($service && $staff && $user) {
            $appoint = new Appoint();

            $appoint->appoint_id = Str::random(12);
            $appoint->service_id = $service->id;
            $appoint->staff_id = $staff->id;
            $appoint->user_id = $user->id;
            $appoint->appoint_date = request('appoint_date');
            $appoint->appoint_start_time = request('appoint_start_time');

            if (request('appoint_end_time')) {
                $serviceTime = $service->time;
                $endTime = date("h:i a", strtotime('+' . $serviceTime . ' minutes', strtotime(request('appoint_end_time'))));
                $appoint->appoint_end_time = $endTime;
            } else {
                $serviceTime = $service->time;
                $endTime = date("h:i a", strtotime('+' . $serviceTime . ' minutes', strtotime(request('appoint_start_time'))));
                $appoint->appoint_end_time = $endTime;
            }

            $appoint->appoint_reminder = request('appoint_reminder') ?? 'active';
            $appoint->save();

            Mail::to($user->email)->send(new AppointCreate($user, $staff, $service, $appoint));

            return response([
                'status' => 'done',
                'message' => 'Successfully booked appointment...',
                'appoint' => $appoint
            ], 201);
        }
    }

    public function userAppoints()
    {
        $user = Auth::user();

        $appoints = Appoint::with(['service', 'staff', 'user'])->where('user_id', $user->id)->get();

        if ($appoints !== null) {
            return response([
                'status' => 'done',
                'appoints' => $appoints
            ], 200);
        } else {
            return response([
                'status' => 'error',
                'message' => 'No appoints found...'
            ], 200);
        }
    }
}
