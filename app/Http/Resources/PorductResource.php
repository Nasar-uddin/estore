<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PorductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "name"=>$this->name,
            "description"=>$this->details,
            "price"=>$this->price,
            "discount"=>$this->discount,
            "actual_price"=>round($this->price-$this->price*($this->discount/100),2),
            "rating"=>$this->reviews->count()>0?round($this->reviews->avg('star'),2):"Not rated yet",
            "Owner"=>$this->user->name,
            "links"=>[
                    "reviews"=>route("reviews.index",$this->id)
                ]
        ];
    }
}
