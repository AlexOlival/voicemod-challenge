<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->tokens()->delete();

        /*
         'local' as a name of a single Personal Access Token for the sake of simplicity
         Typically this is used for distinguishing different devices from where the user may log in from
        */
        return response()->json(['token' => $user->createToken('local')->plainTextToken]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json('You have been successfully logged out.');
    }

    public function update(UpdatePasswordRequest $request)
    {
        $user = $request->user();

        if (! Hash::check($request->old_password, $user->password)) {
            return response()->json(
                'Your old password is incorrect.',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json('You have successfully updated your password.');
    }
}
