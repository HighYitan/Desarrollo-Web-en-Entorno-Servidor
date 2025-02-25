<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\AddressResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\SpaceTypeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SpaceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        // sortida personalitzada del JSON de l'API tractada:
        return [  
            //"identificador" => $this->id,
            "nom" => $this->name,
            "registre" => $this->regNumber,
            "observacions_CA" => $this->observation_CA,
            "observacions_ES" => $this->observation_ES,
            "observacions_EN" => $this->observation_EN,
            "gestor" => new UserResource($this->whenLoaded('user')),
            "tipus" => new SpaceTypeResource(new SpaceTypeResource($this->spaceType)), //Muestro las 3 descripciones porque necesito sus traducciones para la parte cliente.
            "adreça" => new AddressResource($this->whenLoaded('address')),
            "telèfon" => $this->phone,
            "email" => $this->email,
            "www" => $this->website,
            "accessibilitat" => $this->transformAccessType($this->accessType),
            "puntuacióMitjana" => $request->when($this->countScore > 0, function () { // Solo muestra la media si countScore es mayor que 0
                return $this->totalScore / $this->countScore;
            }), // Calcula la media solo si countScore es mayor que 0, si no hay scores no lo muestra
            "modalitats" => $this->modalities->pluck('name')->implode(', '), // Joinea con comas los nombres de las modalidades de la colección
            "serveis" => $this->services->pluck('name')->implode(', '), // Joinea con comas los nombres de los servicios de la colección
            "comentaris" => $this->when($this->relationLoaded('comments') && $this->comments->where('status', 'Y')->isNotEmpty(), function () {
                return CommentResource::collection($this->comments->where('status', 'Y'));
            }), // Solo muestra los comentarios si hay alguno y su status es 'Y'
        ];
    }
    public function with($request)
    {   // permet afegir informació addicional al JSON al 'SpaceController' amb '->additional(['meta' => ...')
        return [
            //'meta' => 'Post ' . $this->id,
            'meta' => "Espai mostrat correctament", // Lo enseña en el JSON al hacer un show()
        ];
    }
    protected function transformAccessType($accessType) // Función para transformar el tipo de acceso a un texto más legible
    {
        switch ($accessType) {
            case 's':
                return 'Sí';
            case 'p':
                return 'Parcialment accessible';
            case 'n':
                return 'No';
            default:
                return $accessType;
        }
    }
}
