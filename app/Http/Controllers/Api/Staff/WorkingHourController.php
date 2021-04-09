<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\WorkingHour;
use Auth;
use Validator;

class WorkingHourController extends Controller
{
    public function update($id)
    {
        $authId = Auth::id();
        $workingHour = WorkingHour::where('user_id', $authId)->where('id', $id)->first();

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
