<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Image;
use Validator;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $user = Auth::user();

        return response([
            'status' => 'done',
            'user' => $user
        ], 200);
    }

    public function update()
    {
        $user = Auth::user();

        $user->name = request('name') ?? $user->name;
        $user->email = request('email') ?? $user->email;
        $user->cc_email = request('cc_email') ?? $user->cc_email;
        $user->description = request('description') ?? $user->description;
        $user->profile_link = request('profile_link') ?? $user->profile_link;
        $user->phone = request('phone') ?? $user->phone;

        $user->update();

        return response([
            'status' => 'done',
            'message' => 'Profile update successful...',
            'user' => $user
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

        $user = Auth::user();

        $img = request()->file('avatar');

        if (!file_exists('assets/images/users')) {
            $dir = mkdir('assets/images/users');
        }

        if ($img->getMimeType() === 'image/png') {
            $avatar_name = 'assets/images/users/user' . $user->id . '.png';
            Image::make($img)->save($avatar_name, 80);
            $user->avatar = request()->getHost() . '/' . $avatar_name;
        } else {
            $avatar_name = 'assets/images/users/user' . $user->id . '.jpg';
            Image::make($img)->save($avatar_name, 80);
            $user->avatar = request()->getHost() . '/' . $avatar_name;
        }

        $user->update();

        return response([
            'status' => 'done',
            'message' => 'Avatar upload successful...',
            'avatar' => $user->avatar,
        ], 202);
    }
}
