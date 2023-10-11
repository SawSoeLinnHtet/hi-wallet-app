<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionDetailsApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        

        return [
            'trx_id' => $this->trx_id,
            'ref_no' => $this->ref_no,
            'amount' => number_format($this->amount, 2) . ' MMK',
            'type' => $this->type,
            'date_time' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s A'),
            'source' => $this->Source ? $this->Source->name : '-',
            'description' => $this->description
        ];
    }
}
