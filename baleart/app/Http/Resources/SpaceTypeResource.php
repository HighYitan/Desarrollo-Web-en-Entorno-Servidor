<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpaceTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "tipusNom" => $this->name,
            "descripció_CA" => $this->description_CA,
            "descripció_ES" => $this->description_ES,
            "descripció_EN" => $this->description_EN,
        ];
    }
}
