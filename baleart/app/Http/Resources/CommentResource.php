<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->mergeWhen($this->relationLoaded('space') && $this->user, [ // Solo se mostrará de esta forma cuando se hagan comentarios a un space
                "registre" => $this->space->regNumber,
                "email" => $this->user->email,
            ]),
            "comentari" => $this->comment,
            "puntuació" => $this->score,
            "imatges" => $this->when($this->images->isNotEmpty(), function () {
                return ImageResource::collection($this->whenLoaded('images'));
            }), // Solo se mostrará si hay imágenes
        ];
    }
}
