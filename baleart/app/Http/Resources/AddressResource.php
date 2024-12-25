<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ZoneResource;
use App\Http\Resources\IslandResource;
use App\Http\Resources\MunicipalityResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "carrer" => $this->name,
            $this->merge(new ZoneResource($this->zone)), // Merge the content of ZoneResource directly into the resource array
            $this->merge(new MunicipalityResource($this->municipality)), // Merge the content of MunicipalityResource directly into the resource array
        ];
    }
}
