<?php

namespace App\Traits;

use App\Mail\ResetPassword;
use App\Models\PasswordReset;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Trait HasResetPassword
 * @package App\Traits
 */
trait HasResetPassword
{
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    public function passwordResets(): MorphMany
    {
        return $this->morphMany(PasswordReset::class, 'user');
    }

    /**
     * generate a token and email user
     *
     * @return void
     */
    public function sendPasswordResetEmail(): void
    {
        $pReset = PasswordReset::generate($this);
        Mail::to($this->getEmailForPasswordReset())->send(
            new ResetPassword($pReset->token, str_replace('App\Models\\', '', $pReset->user_type))
        );
    }

    public function updatePassword($password): void
    {
        $this->password = Hash::make($password);
        if ($this->email_verified_at == null) {
            $this->email_verified_at = now();
        }

        $this->save();
    }
}
