<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);  // sortida rònica de la taula:

        // sortida personalitzada del JSON de l'API tractada:
        return [  
            'identificador' => $this->id,
            'url' => Str::upper($this->name),
        ];
    }
    public function with($request)
    {   // permet afegir informació addicional al JSON al 'PostController' amb '->additional(['meta' => ...')
        return [
            'meta' => 'Image ' . $this->id,
        ];
    }
}
