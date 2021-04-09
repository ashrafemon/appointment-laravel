<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\BreakHour;
use Auth;
use Validator;

class BreakHourController extends Controller
{
    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'day_name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'validation_error',
                'errors' => $validator->errors()
            ], 422);
        }

        $authId = Auth::id();

        $break = new BreakHour();

        $break->user_id = $authId;
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

    public function update($id)
    {
        $authId = Auth::id();

        $break = BreakHour::where('user_id', $authId)->where('id', $id)->first();

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

    public function destroy($id)
    {
        BreakHour::where('id', $id)->where('user_id', Auth::id())->delete();

        return response([
            'status' => 'done',
            'message' => 'Break time delete successful',
        ], 201);
    }
}
