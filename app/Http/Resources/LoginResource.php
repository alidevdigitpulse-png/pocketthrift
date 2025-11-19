<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;
class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = Auth::user();
        $token = $user->createToken('MyApp')->accessToken;
        return [
            'user' => [
                'name' => $this->name,
                'email' => $this->email,
            ],
            'token' => $token
        ];
    }
}
