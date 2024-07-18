<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SignInRequest;
use App\Http\Requests\Api\SignUpRequest;
use App\Http\Requests\Api\UpdateSettingsRequest;
use App\Http\Requests\Api\UpdateUserRequest;
use App\Http\Requests\Api\VerificationSettingsRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService\UserDTO;
use App\Services\UserService\UserService;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserController
 * @package App\Http\Controllers\Api
 */
class UserController extends Controller
{
    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(
        protected UserService $userService
    ) {
    }

    /**
     * @param SignUpRequest $request
     * @return mixed
     */
    public function signUp(SignUpRequest $request)
    {
        $user = $this->userService->createUser(UserDTO::fromRequest($request));
        return response()->json(['user' => UserResource::make($user)]);
    }

    /**
     * @param UpdateUserRequest $request
     * @return mixed
     */
    public function updateUser(UpdateUserRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();

        if (!Hash::check($request->old_password, $user?->password)) {
            return response()->json(['error' => 'wrong password'], 400);
        }

        if ($user && ($request->email || $request->password)) {
            $this->userService->updateUser($user, UserDTO::fromRequest($request));
            return response()->json(['user' => UserResource::make($user)]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * @param UpdateSettingsRequest $request
     * @return mixed
     */
    public function updateSettings(UpdateSettingsRequest $request): mixed
    {
        if ($user = auth()->user()) {
            $code = $this->userService->updateSettings($user, $request->type, $request->settings);
            return response()->json(['code' => $code]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * @param VerificationSettingsRequest $request
     * @return mixed
     */
    public function verificationSettings(VerificationSettingsRequest $request): mixed
    {
        if ($user = auth()->user()) {
            $answer = $this->userService->verificationSettings($user, $request->code);
            return response()->json($answer);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * @return mixed
     */
    public function signOut(): mixed
    {
        if ($user = auth()->user()) {
            if ($user->tokens) {
                $user->tokens()->delete();
            }
            return response()->json();
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * @return mixed
     */
    public function getUser(): mixed
    {
        if ($user = auth()->user()) {
            return response()->json(['user' => UserResource::make($user)]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * @param SignInRequest $request
     * @return mixed
     */
    public function signIn(SignInRequest $request): mixed
    {
        if (!$user = $this->userService->getUserByEmail($request->email)) {
            return response()->json(['error' => 'user not found'], 400);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'wrong password'], 400);
        }

        return $this->authUser($user);
    }

    /**
     * @param User $user
     * @return mixed
     */
    private function authUser(User $user): mixed
    {
        if ($user->tokens) {
            $user->tokens()->delete();
        }
        $token = $user->createToken('authToken');

        return response()
            ->json(['token' => $token->plainTextToken]);
    }
}
