<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Str;
use Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum'], ['only' => 'logout']);
    }

    public function login()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'validation_error',
                'errors' => $validator->errors()
            ], 422);
        }

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $accessToken = Auth::user()->createToken('authToken');
            $user = Auth::user();
            return response([
                'status' => 'done',
                'message' => 'Successfully logged in...',
                'token' => 'Bearer ' . $accessToken->plainTextToken,
                'user' => $user,
            ], 200);
        } else {
            return response([
                'status' => 'error',
                'message' => 'Credentials doesn\'t matched...'
            ], 401);
        }
    }

    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|min: 3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'validation_error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = new User();

        $user->name = request('name');
        $user->email = request('email');
        $user->password = Hash::make(request('password'));
        $user->role_id = 3;
        $user->save();

        return response([
            'status' => 'done',
            'message' => 'Successfully registered...'
        ], 201);
    }

    public function forget()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|exists:users',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'validation_error',
                'errors' => $validator->errors()
            ], 422);
        }

        $dummyPassword = Str::random(16);

        $user = User::where('email', request('email'))->first();
        $user->password = Hash::make($dummyPassword);
        $user->update();

        Mail::to($user->email)->send(new ForgetPasswordMail($user->name, $user->email, $dummyPassword));

        return response([
            'status' => 'done',
            'message' => 'Forget password send in your email address...',
        ], 202);
    }

    public function logout()
    {
        $auth = Auth::user();

        $auth->tokens()->delete();

        return response([
            'status' => 'done',
            'message' => 'Successfully logout...',
        ], 200);

    }
}
