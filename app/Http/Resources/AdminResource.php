<?php

namespace App\Http\Resources;

use App\Models\UserInformation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->whenLoaded('roles', fn() => $this->roles->first()?->name),
            'user_information' => UserInformation::query()
                ->where('id', $this->user_information_id)
                ->first(),
        ];
    }
}
