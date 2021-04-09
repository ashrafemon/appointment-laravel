<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\Timeoff;
use Auth;
use Validator;

class TimeoffController extends Controller
{
    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'desc' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'validation_error',
                'errors' => $validator->errors()
            ], 422);
        }

        $authId = Auth::id();

        $timeoff = new Timeoff();
        $timeoff->user_id = $authId;
        $timeoff->desc = request('desc');
        $timeoff->start_date = request('start_date');
        $timeoff->end_date = request('end_date');
        $timeoff->save();

        return response([
            'status' => 'done',
            'message' => 'Timeoff added successfully',
            'timeOff' => $timeoff
        ], 201);
    }

    public function update($id)
    {
        $authId = Auth::id();

        $timeoff = Timeoff::where('user_id', $authId)->where('id', $id)->first();

        $timeoff->desc = request('desc') ?? $timeoff->desc;
        $timeoff->start_date = request('start_date') ?? $timeoff->start_date;
        $timeoff->end_date = request('end_date') ?? $timeoff->end_date;
        $timeoff->all_day = request('all_day') ?? $timeoff->all_day;
        $timeoff->update();

        return response([
            'status' => 'done',
            'message' => 'Timeoff updated successfully',
            'timeOff' => $timeoff
        ], 202);
    }

    public function destroy($id)
    {
        Timeoff::where('user_id', Auth::id())->where('id', $id)->delete();

        return response([
            'status' => 'done',
            'message' => 'Timeoff deleted successfully',
        ], 202);
    }
}
