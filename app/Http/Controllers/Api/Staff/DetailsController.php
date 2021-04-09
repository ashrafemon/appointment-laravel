<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\User;

class DetailsController extends Controller
{
    public function index()
    {
        $authId = Auth::id();
        
        $staff = User::with(['workinghours', 'breakhours', 'timeoffs', 'userservices', 'workingstatuses'])->where('id',$authId)->first();

        return response([
            'status' => 'done',
            'staff' => $staff,
        ]);
    }

    public function update()
    {
        $staff = Auth::user();

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

    public function change_password()
    {
        $validator = Validator::make(request()->all(), [
            'password' => 'required|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'validation_error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $auth = Auth::user();

        $auth->password = Hash::make(request('password'));
        $auth->update();

        $auth->tokens()->delete();

        return response([
            'status' => 'done',
            'message' => 'Password change successful...',
        ], 202);
    }

    public function upload_avatar()
    {
        $validator = Validator::make(request()->all(), [
            'avatar' => 'required|image'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'validation_error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $staff = Auth::user();

        $img = request()->file('avatar');

        if (!file_exists('assets/images/users')) {
            $dir = mkdir('assets/images/users');
        }

        if ($img->getMimeType() === 'image/png') {
            $avatar_name = 'assets/images/users/user' . $staff->id . '.png';
            Image::make($img)->save($avatar_name, 80);
            $staff->avatar = request()->getHost() . '/' . $avatar_name;
        } else {
            $avatar_name = 'assets/images/users/user' . $staff->id . '.jpg';
            Image::make($img)->save($avatar_name, 80);
            $staff->avatar = request()->getHost() . '/' . $avatar_name;
        }

        $staff->update();

        return response([
            'status' => 'done',
            'message' => 'Avatar upload successful...',
            'avatar' => $staff->avatar,
        ], 202);
    }
}
