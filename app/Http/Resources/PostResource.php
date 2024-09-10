<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'content'=>$this->content,
           // 'category_title'=>$this->category->title,
            //'category'=>$this->category_id,
            //'tag'=>$this->tag_id,
            'comments'=> CommentResource::collection($this->whenLoaded('comment')),
            'categories'=> CategoryResource::collection($this->whenLoaded('categories')),
            'tags'=> TagResource::collection($this->whenLoaded('tags')),
            'slug'=>$this->slug,
            'author'=>$this->author->name
        ];
    }
}
