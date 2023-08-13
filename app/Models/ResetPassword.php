<?php

namespace App\Models;

use App\Contracts\CanResetPsOrVerifyAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

/**
 * @property CanResetPsOrVerifyAccount $user
 */
class ResetPassword extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $casts = ['created_at' => 'datetime'];

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    protected $guarded = [];

    public static function generate(CanResetPsOrVerifyAccount $for): self
    {
        return $for->passwordResets()->create([
            'token' => Str::random(50),
            'created_at' => now(),
        ]);
    }

    /**
     * @return MorphTo
     */
    public function user(): MorphTo
    {
        return $this->morphTo();
    }
}
