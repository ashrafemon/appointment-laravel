<?php

namespace App\Http\Controllers\Api\Admin\Staff;

use App\Http\Controllers\Controller;
use App\Models\Timeoff;

class TimeoffController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'adminRoleChecker'], ['only' => ['store', 'update', 'destroy']]);
    }

    public function index($user_id)
    {
        $timeoffs = Timeoff::where('user_id', $user_id)->get();

        return response([
            'status' => 'done',
            'timeOffs' => $timeoffs
        ], 200);
    }

    public function store($user_id)
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

        $timeoff = new Timeoff();
        $timeoff->user_id = $user_id;
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

    public function update($user_id, $id)
    {
        $timeoff = Timeoff::where('user_id', $user_id)->where('id', $id)->first();

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

    public function destroy($user_id, $id)
    {
        Timeoff::where('user_id', $user_id)->where('id', $id)->delete();

        return response([
            'status' => 'done',
            'message' => 'Timeoff deleted successfully',
        ], 202);
    }
}
