<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class UserApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $unread_noti_count = Auth::User()->unreadNotifications()->count();

        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'account' => $this->wallet ? $this->wallet->account_number : '',
            'balance' => $this->wallet ? number_format($this->wallet->amount) : '',
            'profile' => asset('frontend/img/user.png'),
            'receive_qr_value' => $this->phone,
            'unread_noti_count' => $unread_noti_count
        ];
    }
}
