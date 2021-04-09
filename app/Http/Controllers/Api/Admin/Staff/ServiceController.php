<?php

namespace App\Http\Controllers\Api\Admin\Staff;

use App\Http\Controllers\Controller;
use App\Models\UserService;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'adminRoleChecker'], ['only' => ['store']]);
    }

    public function index($id)
    {
        $services = UserService::where('user_id', $id)->first();

        return response([
            'status' => 'done',
            'services' => $services
        ], 200);
    }

    public function store($id)
    {
        $service = UserService::where('user_id', $id)->first();

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
