<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'id' => $this->id,
            'name' => $this->name,
            'user' => new UserResource($this->whenLoaded('user')),
            'posts_count' => $this->relationLoaded('posts') ? count($this->whenLoaded('posts')) : 0,
            'posts' => $this->whenLoaded('posts'),
            'created_at' => $this->created_at->format('Y-m-d H:m:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:m:s'),
            'deleted_at' => $this->updated_at->format('Y-m-d H:m:s')
        ];
    }
}
