<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserInformation;
use App\Models\VerifyAccount;
use App\Traits\HasResetPsOrVerifyAccount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class UserController extends Controller
{
    /**
     *
     * @return AnonymousResourceCollection
     */
    public function getUsers(): AnonymousResourceCollection
    {
        $users = User::get();
        return UserResource::collection($users);
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getCurrentUser(Request $request)
    {
        return response()->json([
            'data' => $request->user(),
        ]);
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        //        try {
        //            $mobile = PhoneNumber::__construct($request->mobile, 'SA')->formatE164();
        //        } catch (Exception $e) {
        //            return response()->json(['message' => 'invalid mobile number'], 400);
        //        }
        //        $request->merge(['mobile' => $mobile]);
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);
        /** @var User|HasRoles|HasResetPsOrVerifyAccount $user */

        // Create a Temp UserInformation record
        $tempUserInformation = UserInformation::factory()->create();

        $user = User::query()->create([
            'username' =>
                Str::upper(Str::substr($request->email, 0, 1)) .
                Str::substr(Str::lower(explode('@', $request->email)[0]), 1),
            'email' => Str::lower($request->email),
            'password' => Hash::make(Str::random(40)), // random
            'user_information_id' => $tempUserInformation->id,
        ]);

        event(new Registered($user));
        $user->sendEmail(VerifyAccount::class);

        return response()->json(['message' => 'User created successfully.']);
    }
}
