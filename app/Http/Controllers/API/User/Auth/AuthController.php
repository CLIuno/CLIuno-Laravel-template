<?php

namespace App\Http\Controllers\API\User\Auth;

use App\Contracts\SanctumAuthControllerContract;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends SanctumAuthControllerContract
{
    public function getModel(): Model
    {
        return new User();
    }

    public function getGuard()
    {
        return 'user-api';
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = $this->getModel()
            ->query()
            ->where('email', $request->email)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(
                [
                    'message' => 'The provided credentials are incorrect.',
                ],
                401
            );
        }

        // if user not active
        if (!$user->is_active) {
            return response()->json(
                [
                    'message' => ['the user is not active.'],
                ],
                401
            );
        }

        if ($user->email_verified_at == null) {
            return response()->json(
                [
                    'message' => ['the email is not verified.'],
                ],
                401
            );
        }

        return response()->json([
            'token' => $user->createToken($this->getGuard())->plainTextToken,
            'user' => new UserResource($user),
        ]);
    }
}
