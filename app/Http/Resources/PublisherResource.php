<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublisherResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->user->email,
            'active' => $this->active,
            'created_at' => Carbon::parse($this->created_at)->format('m-d-Y g:i A'),
            'updated_at' => Carbon::parse($this->updated_at)->format('m-d-Y g:i A'),
        ];
    }
}
