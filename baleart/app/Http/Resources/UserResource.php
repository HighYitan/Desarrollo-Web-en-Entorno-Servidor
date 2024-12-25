<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\RoleResource;
use App\Http\Resources\SpaceResource;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Resources\Json\JsonResource;

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
            "nom" => $this->name,
            "cognom" => $this->lastName,
            "email" => $this->email,
            "telèfon" => $this->phone,
            $this->mergeWhen($this->relationLoaded('role'), new RoleResource($this->role)), // Hace el merge solo si la relación está cargada
            'espais' => $this->when($this->spaces->isNotEmpty() && !$this->isFromApiSpace($request), function () { // Solo muestra los espacios si tiene alguno y no viene de la ruta space porque si no se muestra 2 veces
                return SpaceResource::collection($this->whenLoaded('spaces'));
            }),
            'comentaris' => $this->when($this->comments->where('status', 'Y')->isNotEmpty(), function () { // Solo muestra los comentarios si tiene alguno
                return CommentResource::collection($this->comments->where('status', 'Y'));
            }),
        ];
    }
    private function isFromApiSpace(Request $request): bool // Comprueba si la ruta viene de la API de Space y evita duplicados en la respuesta al llamar desde Space
    {
        return strpos($request->getPathInfo(), '/api/space') !== false; // Si la ruta contiene '/api/space' devuelve un valor que no es false (La posición del substring en la cadena) por eso es necesario el !== false
    }
}
