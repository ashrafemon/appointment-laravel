<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use App\Models\WorkingStatus;
use Carbon\Carbon;

class WorkingStatusController extends Controller
{
    public function store(){
        $authId = Auth::id();

        $workingStatus = new WorkingStatus();
        
        $workingStatus->user_id = $authId;
        $workingStatus->day_name = request('day_name');
        $workingStatus->today = request('today');
        $workingStatus->start_time = request('start_time');
        $workingStatus->end_time = '';

        $workingStatus->save();

        return response([
            'status' => 'done',
            'message' => 'You have started to work...',
            'workingStatus' => $workingStatus
        ], 201);
    }

    public function update($id){
        $authId = Auth::id();

        $workingStatus = WorkingStatus::where('id', $id)->where('user_id', $authId)->first();
        
        $workingStatus->user_id = $authId;
        $workingStatus->day_name = request('day_name') ?? $workingStatus->day_name;
        $workingStatus->today = request('today') ?? $workingStatus->today;
        $workingStatus->start_time = request('start_time') ?? $workingStatus->start_time;
        $workingStatus->end_time = request('end_time');

        $workingStatus->update();

        return response([
            'status' => 'done',
            'message' => 'You have stopped to work...',
            // 'workingStatus' => $workingStatus
        ], 201);
    }

    public function latestStatus(){
        $authId = Auth::id();

        $workingStatus = WorkingStatus::where('user_id', $authId)
                                    ->where('end_time', '')
                                    ->latest('today', Carbon::today())
                                    ->first();

        if($workingStatus !== null){
            return response([
                'status' => 'done',
                'message' => 'Latest working status found',
                'workingStatus' => $workingStatus
            ]);
        }else{
            return response([
                'status' => 'error',
                'message' => 'You have not any working status',
            ]);
        }
    }
}
