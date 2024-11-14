<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class ListingResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'listing';
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {

        $images = [];
        foreach ($this->uploads as $image) {

            $images[] = is_dir('images') ? config('app.url') . "/images$image->url" : $image->url;
        }
        return [

            'ref' => $this->ref,
            'title' => $this->title,
            'location' => $this->location,
            'propertyStatus' => $this->property_status,
            'images' => $images,
            'propertyType' => $this->property_type,
            'price' => $this->price,
            'description' => $this->description,
        ];
    }
}
