<?php

namespace App\Contracts;

interface CanResetPsOrVerifyAccount
{
    public function getEmail();
    public function sendEmail($model);
    public function passwordResets();
    public function verifyAccounts();
    public function verifyAccount($attribute);
    public function updatePassword($password);
}
