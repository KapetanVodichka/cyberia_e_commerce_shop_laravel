<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller {
    public function index() {
        $users = User::where('role', 'customer')->paginate(10);
        return UserResource::collection($users);
    }

    public function profile() {
        return new UserResource(auth()->user());
    }
}
