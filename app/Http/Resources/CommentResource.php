<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'post_id' => $this->post_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'message' => $this->message,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:m:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:m:s') : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-m-d H:m:s') : null
        ];
    }
}
