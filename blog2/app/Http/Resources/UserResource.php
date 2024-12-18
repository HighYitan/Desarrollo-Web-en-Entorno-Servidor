<?php

namespace App\Http\Resources;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
            'identificador' => $this->id,
            'nom' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'creacio' => Carbon::parse($this->created_at)->format("d-m-Y h:m:s"),
            'posts' => PostResource::collection($this->whenLoaded('posts')), // utilitza 'PostResource' per a cada post
            'comentaris' => CommentResource::collection($this->whenLoaded('comments')), // utilitza 'CommentResource' per a cada post
        ];
    }
}
