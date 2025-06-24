<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'priceRub' => $this->priceRub,
            'stockAmount' => $this->stockAmount,
            'available' => $this->available,
            'subcategory_id' => $this->subcategory_id,
            'timesPurchased' => $this->timesPurchased,
            'images' => ImagesCollection::collection($this->whenLoaded('images')),
            'specifications' => SpecificationsCollection::collection($this->whenLoaded('productSpecifications')),
            'reviews' => ReviewsCollection::collection($this->whenLoaded('reviews'))
        ];
    }
}
