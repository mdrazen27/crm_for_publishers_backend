<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'role_id' => $this->role_id,
            'publisherName' => $this->when($this->publisher, $this->publisher->name),
            'created_at' => Carbon::parse($this->created_at)->format('m-d-Y g:i A'),
            'updated_at' => Carbon::parse($this->updated_at)->format('m-d-Y g:i A'),
        ];
    }
}
