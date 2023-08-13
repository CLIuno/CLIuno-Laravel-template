<?php

namespace App\Http\Resources;

use App\Models\UserInformation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'user_information' => UserInformation::query()
                ->where('id', $this->user_information_id)
                ->first(),
        ];
    }
}
