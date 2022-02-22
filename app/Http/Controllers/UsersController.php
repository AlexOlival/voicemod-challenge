<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\CreateUserRequest;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $users = $this->userRepository->all();

        return UserResource::collection($users);
    }

    public function store(CreateUserRequest $request)
    {
        $data = array_merge(
            $request->only(['name', 'surnames', 'email', 'country', 'phone']),
            ['password' => bcrypt($request->get('password'))]
        );

        $user = $this->userRepository->create($data);

        return new UserResource($user);
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $data = $request->only(['name', 'surnames', 'email', 'country', 'phone', 'postal_code']);

        if (!$this->userRepository->update($user, $data)) {
            return response()->json(
                'There was a problem updating the user.',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        if (!$this->userRepository->delete($user)) {
            return response()->json(
                'There was a problem deleting the user.',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json('The user was successfully deleted.');
    }
}
