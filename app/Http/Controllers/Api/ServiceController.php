<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Support\Str;
use Image;
use Validator;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'adminRoleChecker'], ['only' => ['store', 'update', 'destroy']]);
    }

    public function index()
    {
        $services = Service::all();

        return response([
            'status' => 'done',
            'services' => $services
        ], 200);
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'cost' => 'required',
            'time' => 'required',
            'category_id' => 'required',
            'access' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'validation_error',
                'errors' => $validator->errors()
            ], 422);
        }

        $service = new Service();

        $service->category_id = request('category_id');
        $service->name = request('name');
        $service->slug = Str::slug(request('name'));
        $service->desc = request('desc');
        $service->cost = request('cost');
        $service->time = request('time');
        $service->buffer_time = request('buffer_time');
        $service->access = request('access');
        $service->url = 'services/' . Str::slug(request('name'));

        $this->imageUpload($service);

        $service->save();

        return response([
            'status' => 'done',
            'message' => 'Service added successfully',
            'service' => $service
        ], 201);
    }

    public function update($slug)
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'cost' => 'required',
            'time' => 'required',
            'category_id' => 'required',
            'access' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'validation_error',
                'errors' => $validator->errors()
            ], 422);
        }

        $service = Service::where('slug', $slug)->first();

        if ($service !== null) {
            $service->name = request('name') ?? $service->name;
            $service->slug = Str::slug(request('name')) ?? $service->slug;
            $service->desc = request('desc') ?? $service->desc;
            $service->cost = request('cost') ?? $service->cost;
            $service->time = request('time') ?? $service->time;
            $service->buffer_time = request('buffer_time') ?? $service->buffer_time;
            $service->category_id = request('category_id') ?? $service->category_id;
            $service->access = request('access') ?? $service->access;
            $service->url = Str::slug(request('name')) ?? $service->url;

            $this->imageUpload($service);

            $service->update();

            return response([
                'status' => 'done',
                'message' => 'Service update successfully',
                'service' => $service
            ], 202);
        } else {
            return response([
                'status' => 'error',
                'message' => 'There is no such a service like this name...',
            ], 404);
        }
    }

    public function destroy($slug)
    {
        $service = Service::where('slug', $slug)->first();

        if ($service !== null) {
            $service->delete();
            return response([
                'status' => 'done',
                'message' => 'Service delete successfully'
            ], 202);
        } else {
            return response([
                'status' => 'error',
                'message' => 'There is no such a service like this name...',
            ], 404);
        }
    }

    public function validateField()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'cost' => 'required',
            'time' => 'required',
            'category_id' => 'required',
            'access' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'validation_error',
                'errors' => $validator->errors()
            ], 422);
        }
    }

    public function imageUpload($service)
    {
        $avatar = request()->file('avatar');

        if (!file_exists('assets/images/services')) {
            $dir = mkdir('assets/images/services');
        }

        if ($avatar) {
            $avatar_name = 'assets/images/services/' . $service->slug . '.jpg';
            Image::make($avatar)->save($avatar_name, 60);
            $service->avatar = request()->getHost() . '/' . $avatar_name;
        }
    }
}
