<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\IslandResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MunicipalityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "municipi" => $this->name,
            $this->merge(new IslandResource($this->island)), // Mergea el contenido de la clase IslandResource
        ];
    }
}
