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

            if (config('app.filesystem_disk') == 'local') {
                $images[] = config('app.url') . "/$image->url";
            }
        }
        return [
            'id' => $this->id,
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
