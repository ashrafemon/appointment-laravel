<?php

namespace App\Http\Controllers\Api\Admin\Staff;

use App\Http\Controllers\Controller;
use App\Models\WorkingStatus;

class WorkingStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'adminRoleChecker']);
    }

    public function index($user_id)
    {
        $works = WorkingStatus::where('user_id', $user_id)->latest()->get();

        return response([
            'status' => 'done',
            'workingStatuses' => $works
        ], 200);
    }
}
