<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\Resource;

class ProductCollection extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "product_id"=>$this->id,
            "name"=>$this->name,
            "price"=>$this->price,
            "actual_price"=>round($this->price-($this->price*$this->discount/100),2),
            "owner"=>$this->user->name,
            "rating"=>$this->reviews->count()>0?round($this->reviews->avg('star'),2):0,
            "links"=>[
                "show"=>route("product.show",$this->id)
            ]
        ];
    }
}
