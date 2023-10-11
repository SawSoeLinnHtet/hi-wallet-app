<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $title = $this->type === 'income' ? 'From ' . $this->Source->name : 'To ' . $this->Source->name;
        
        return [
            'trx_id' => $this->trx_id,
            'amount' => number_format($this->amount, 2) . ' MMK',
            'type' => $this->type,
            'title' => $title,
            'date' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s A')
        ];
    }
}
