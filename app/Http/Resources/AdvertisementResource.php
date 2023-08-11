<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisementResource extends JsonResource
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
            'name' => $this->name,
            'url' => $this->url,
            'active' => $this->active,
            'created_at' => Carbon::parse($this->created_at)->format('m-d-Y g:i A'),
            'updated_at' => Carbon::parse($this->updated_at)->format('m-d-Y g:i A'),
            'publisher' => PublisherResource::make($this->publisher),
        ];
    }
}
