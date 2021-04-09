<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\UserService;
use Auth;

class ServiceController extends Controller
{
    public function store()
    {
        $authId = Auth::id();

        $service = UserService::where('user_id', $authId)->first();
        $reqServices = request('services');
        $service->services = $reqServices;
        $service->update();

        return response([
            'status' => 'done',
            'message' => 'Services added successfully',
            'services' => $service
        ], 201);
    }
}
