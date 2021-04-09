<?php

namespace App\Http\Controllers\Api\Admin\Staff;

use App\Http\Controllers\Controller;
use App\Mail\StaffCreate;
use App\Models\User;
use App\Models\UserService;
use App\Models\WorkingHour;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Image;
use Validator;

class DetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'adminRoleChecker'], ['only' => ['store', 'update', 'destroy', 'upload_avatar']]);
    }

    public function index()
    {
        $staffs = User::with(['workinghours', 'breakhours', 'timeoffs', 'userservices', 'workingstatuses'])
            ->where('role_id', 2)->get();

        return response([
            'status' => 'done',
            'staffs' => $staffs,
        ]);
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|min: 3',
            'email' => 'required|email|unique:users',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'validation_error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = new User();

        $dummyPassword = Str::random(8);

        $user->name = request('name');
        $user->email = request('email');
        $user->password = Hash::make($dummyPassword);
        $user->role_id = 2;
        $user->save();

        if ($user) {
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

            foreach ($days as $day) {
                WorkingHour::create([
                    'user_id' => $user->id,
                    'day_name' => $day,
                    'start_time' => '11:00 am',
                    'end_time' => '05:00 pm'
                ]);
            }

            UserService::create([
                'user_id' => $user->id,
                'services' => []
            ]);

            $subject = 'Staff Create';

            Mail::to($user->email)->send(new StaffCreate($user, $dummyPassword, $subject));

            $workinghours = $user->workinghours;
            $breakhours = $user->breakhours;
            $timeoffs = $user->timeoffs;
            $userservices = $user->userservices;

            return response([
                'status' => 'done',
                'message' => 'Staff added successfully',
                'staff' => $user
            ], 201);
        }
    }

    public function show($id)
    {
        $staff = User::with(['workinghours', 'breakhours', 'timeoffs', 'userservices', 'workingstatuses'])
            ->where('role_id', 2)->where('id', $id)->first();

        return response([
            'status' => 'done',
            'staff' => $staff,
        ], 200);
    }

    public function update($id)
    {
        $staff = User::where('role_id', 2)->where('id', $id)->first();

        $staff->name = request('name') ?? $staff->name;
        $staff->email = request('email') ?? $staff->email;
        $staff->cc_email = request('cc_email') ?? $staff->cc_email;
        $staff->description = request('description') ?? $staff->description;
        $staff->profile_link = request('profile_link') ?? $staff->profile_link;
        $staff->phone = request('phone') ?? $staff->phone;
        $staff->update();

        return response([
            'status' => 'done',
            'message' => 'Staff updated successfully',
            'staff' => $staff,
        ], 202);
    }

    public function upload_avatar($id)
    {
        $validator = Validator::make(request()->all(), [
            'avatar' => 'required|image'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'validation_error',
                'errors' => $validator->errors()
            ], 422);
        }

        $img = request()->file('avatar');

        $staff = User::where('role_id', 2)->where('id', $id)->first();

        if (!file_exists('assets/images/users')) {
            $dir = mkdir('assets/images/users');
        }

        if ($img) {
            if ($img->getMimeType() === 'image/png') {
                $avatar_name = 'assets/images/users/staff' . $id . '.png';
                Image::make($img)->save($avatar_name, 80);
                $staff->avatar = request()->getHost() . '/' . $avatar_name;
            } else {
                $avatar_name = 'assets/images/users/staff' . $id . '.jpg';
                Image::make($img)->save($avatar_name, 80);
                $staff->avatar = request()->getHost() . '/' . $avatar_name;
            }

            $staff->update();

            return response([
                'status' => 'done',
                'message' => 'Staff avatar updated successfully',
                'avatar' => $staff->avatar
            ], 201);
        }
    }

    public function destroy($id)
    {
        $staff = User::with(['workinghours', 'breakhours', 'timeoffs', 'userservices'])
            ->where('role_id', 2)->where('id', $id)->first();

        $staff->workinghours->each(function ($item) {
            $item->delete();
        });

        $staff->breakhours->each(function ($item) {
            $item->delete();
        });

        $staff->timeoffs->each(function ($item) {
            $item->delete();
        });

        $staff->userservices->delete();

        $staff->delete();

        return response([
            'status' => 'done',
            'message' => 'Staff deleted successfully',
        ], 202);
    }
}
