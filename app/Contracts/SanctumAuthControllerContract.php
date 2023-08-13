<?php

namespace App\Contracts;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Services\TamkeenSMSService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

abstract class SanctumAuthControllerContract extends Controller
{
    abstract public function getModel(): Model;
    abstract public function getGuard();

    /**
     * @param Request $request
     * @return JsonResponse
     * @lrd:start
     * validate the OTP code
     * this route is used to validate the OTP code sent to the user
     * @lrd:end
     * @QAparam otp required
     * @QAparam email required
     */
    public function validateOtp(Request $request): JsonResponse
    {
        $request->validate([
            'otp' => 'required',
            'email' => 'required',
        ]);

        $user = $this->getModel()
            ->query()
            ->where('email', Str::lower($request->email))
            ->first();

        if (!$user) {
            return response()->json(
                [
                    'message' => 'The provided credentials are incorrect.',
                ],
                404
            );
        }

        // check the cache
        if (!cache("otp-$user->id")) {
            return response()->json(['message' => 'not allowed request'], 401);
        }
        cache()->forget("otp-$user->id");

        // validate otp code
        if (!TamkeenSMSService::check($user->mobile, $request->otp)) {
            return response()->json(['message' => 'invalid otp code'], 401);
        }

        return response()->json(['token' => $user->createToken($request->email)->plainTextToken, 'user' => $user]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @lrd:start
     * get a trainee
     * this route is used to get a trainee and should pass the parameter id
     * @lrd:end
     * @QAparam token required
     * @QAparam password required | min:8
     * @QAparam password_confirmation required | min:8
     */
    public function doResetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->uncompromised(),
                'confirmed',
                Password::defaults(),
            ],
        ]);
        $passwordReset = PasswordReset::query()
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset) {
            return response()->json(['message' => 'token not found.'], 401);
        }

        $passwordReset->delete(); // delete it as it was consumed
        if ($passwordReset->created_at->diffInHours() > 12) {
            return response()->json(['token is expired.'], 401);
        }

        $passwordReset->user->updatePassword($request->password);

        return response()->json(['message' => 'password has been reset successfully.']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @lrd:start
     * forget password route
     * this route is used to send email to the user to reset his password
     * @lrd:end
     * @QAparam email required unique
     * @QAparam national_id required  bigInteger unique
     */
    public function forgetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required',
            'national_id' => ['required', 'string'],
        ]);

        $user = $this->getModel()
            ->query()
            ->where('email', $request->email)
            ->first();

        if (!$user || $user->national_id != $request->national_id) {
            return response()->json(
                [
                    'message' => 'The provided credentials are incorrect.',
                ],
                401
            );
        }
        /**
         * @var $user Trainee | Manager
         */
        $user->sendPasswordResetEmail();
        return response()->json([
            'message' => 'reset password link has been sent to your email.',
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @lrd:start
     * change password route
     * this route is used to change the password of the user
     * @lrd:end
     * @QAparam old_password required
     * @QAparam password required | min:8 | confirmed
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'old_password' => ['required', 'string', 'min:8'],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->uncompromised(),
                'confirmed',
                Password::defaults(),
            ],
        ]);
        /** @var Model & CanResetPassword $user */
        $user = auth()->user();
        if (!$user) {
            return response()->json(
                [
                    'message' => 'user not found.',
                ],
                401
            );
        }

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(
                [
                    'message' => 'The provided credentials are incorrect.',
                ],
                400
            );
        }
        $user->updatePassword($request->password);

        return response()->json([
            'message' => 'password has been changed.',
        ]);
    }
}
