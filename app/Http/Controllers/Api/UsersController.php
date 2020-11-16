<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function show(Request $request)
    {
        return new UserResource($request->user());
    }

    public function update(Request $request)
    {
        $this->validateUser($request);

        $user = $request->user();

        // Password
        if ($request->filled(['password', 'old_password'])) {
            if (! Hash::check(
                $request->input('old_password'),
                $user->password
            )) {
                throw ValidationException::withMessages([
                    'The old password does not match your current password.'
                ]);
            }

            $user->password = bcrypt($request->input('password'));
        }

        // Name
        if ($request->filled('name')) {
            $user->name = $request->input('name');
        }

        // Email
        if ($request->filled('email')) {
            // Todo: Verify email address...
            $user->email = $request->input('email');
        }

        $user->save();

        return new UserResource($user);
    }

    protected function validateUser(Request $request)
    {
        $request->validate([
            'name' => 'filled',
            'email' => 'filled|email', // Todo: Unique
            'password' => 'filled|min:6',
            'old_password' => 'required_with:password'
        ]);
    }
}
