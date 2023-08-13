<?php

namespace App\Traits;

use App\Mail\ResetPassword;
use App\Mail\VerifyEmail;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\UserInformation;
use App\Models\VerifyAccount;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Trait HasResetPsOrVerifyAccount
 * @package App\Traits
 */
trait HasResetPsOrVerifyAccount
{
    /**
     * @return MorphMany
     */
    public function passwordResets(): MorphMany
    {
        return $this->morphMany(PasswordReset::class, 'user');
    }

    /**
     * @return MorphMany
     */
    public function verifyAccounts(): MorphMany
    {
        return $this->morphMany(VerifyAccount::class, 'user');
    }

    /**
     * generate a token and email user
     *
     * @param $model
     * @return void
     */
    public function sendEmail($model): void
    {
        $generate = $model::generate($this);
        $mail = $model == PasswordReset::class ? ResetPassword::class : VerifyEmail::class;
        Mail::to($this->getEmail())->send(
            new $mail($generate->token, str_replace('App\Models\\', '', $generate->user_type))
        );
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function updatePassword($password): void
    {
        $this->password = Hash::make($password);
        if ($this->email_verified_at == null) {
            $this->email_verified_at = now();
        }

        $this->save();
    }

    /**
     * TODO: clean this
     */
    public function verifyAccount($attribute): void
    {
        $userID = VerifyAccount::query()
            ->where('token', $attribute['token'])
            ->first();
        $user = User::find($userID->user_id);
        $userInfo = UserInformation::query()
            ->where('id', $user->user_information_id)
            ->first();
        unset($attribute['token']);
        $userInfo->update($attribute);
        $this->password = Hash::make($attribute['password']);
        if ($this->email_verified_at == null) {
            $this->email_verified_at = now();
        }

        $this->save();
    }
}
