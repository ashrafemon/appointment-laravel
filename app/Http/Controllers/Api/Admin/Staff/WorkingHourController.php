<?php

namespace App\Http\Controllers\Api\Admin\Staff;

use App\Http\Controllers\Controller;
use App\Models\WorkingHour;

class WorkingHourController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'adminRoleChecker'], ['only' => ['update']]);
    }

    public function index($user_id)
    {
        $workingHours = WorkingHour::where('user_id', $user_id)->get();

        return response([
            'status' => 'done',
            'workingHours' => $workingHours
        ], 200);
    }

    public function update($user_id, $id)
    {
        $workingHour = WorkingHour::where('user_id', $user_id)->where('id', $id)->first();

        $workingHour->day_name = request('day_name') ?? $workingHour->day_name;
        $workingHour->start_time = request('start_time') ?? $workingHour->start_time;
        $workingHour->end_time = request('end_time') ?? $workingHour->end_time;
        $workingHour->status = request('status') ?? $workingHour->status;
        $workingHour->update();

        return response([
            'status' => 'done',
            'message' => 'Working hour updated successfully',
            'workingHour' => $workingHour
        ], 202);
    }
}
