<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
          'blog_id' => $this->blog_id,
          'blog' => new BlogResource($this->whenLoaded('blog')),
          'comments' => CommentResource::collection($this->whenLoaded('comments')),
          'subject' => $this->subject,
          'body' => $this->body,
          'created_at' => $this->created_at->format('Y-m-d H:m:s'),
          'updated_at' => $this->updated_at
        ];
    }
}
