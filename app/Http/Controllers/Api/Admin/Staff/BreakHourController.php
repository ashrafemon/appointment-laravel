<?php

namespace App\Http\Controllers\Api\Admin\Staff;

use App\Http\Controllers\Controller;
use App\Models\BreakHour;
use Validator;

class BreakHourController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'adminRoleChecker'], ['only' => ['store', 'update', 'destroy']]);
    }

    public function index($user_id)
    {
        $breakHours = BreakHour::where('user_id', $user_id)->get();

        return response([
            'status' => 'done',
            'breakHours' => $breakHours
        ], 200);
    }

    public function store($user_id)
    {
        $validator = Validator::make(request()->all(), [
            'day_name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'validation_error',
                'breakHour' => $validator->errors(),
            ], 422);
        }

        $break = new BreakHour();
        $break->user_id = $user_id;
        $break->day_name = request('day_name');
        $break->start_time = request('start_time');
        $break->end_time = request('end_time');
        $break->save();

        return response([
            'status' => 'done',
            'message' => 'Break hour added successfully',
            'breakHour' => $break
        ], 201);
    }

    public function update($user_id, $id)
    {
        $break = BreakHour::where('user_id', $user_id)->where('id', $id)->first();
        $break->day_name = request('day_name') ?? $break->day_name;
        $break->start_time = request('start_time') ?? $break->start_time;
        $break->end_time = request('end_time') ?? $break->end_time;
        $break->update();

        return response([
            'status' => 'done',
            'message' => 'Break hour updated successfully',
            'breakHour' => $break
        ], 202);
    }

    public function destroy($user_id, $id)
    {
        BreakHour::where('user_id', $user_id)->where('id', $id)->delete();
        return response([
            'status' => 'done',
            'message' => 'Break hour deleted successfully',
        ], 202);
    }
}
