<?php

namespace App\Http\Controllers\Api;

use App\Models\Space;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SpaceResource;

class SpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Space::query();
        // Filtrar per 'illa' si el paràmetre existeix a la consulta: baleart.test/api/space?illa=menorca per exemple
        if ($request->has('illa')) { //Si el parámetro 'illa' existe en la consulta
            $query->whereHas('address.municipality.island', function ($q) use ($request) { //Hace una consulta a la tabla 'islands' a través de la tabla 'municipalities' a través de la tabla 'addresses' a través de la tabla 'spaces'.
                $q->where('name', 'like', '%' . $request->illa . '%'); //Busca el nombre de la isla que contenga el valor del parámetro 'illa'.
            });
        }
        $query->orderBy('id');
        $query->with([
            "address",
            "modalities",
            "services",
            "spaceType",
            "comments",
            "comments.images", //Solo se necesita esta línea para cargar comments y images pero dejo la anterior porque así se ve más claro por separado lo que se quiere mostrar.
            "user"
        ]);
        $spaces = $query->get();

        return (SpaceResource::collection($spaces))->additional(['meta' => 'Spaces mostrats correctament']);  // torna una resposta personalitzada
    }

    /**
     * Display the specified resource.
     */
    //public function show(string $id)
    public function show(Space $space)
    {
        // SELECCIÓ DE LES DADES
        $space->load([
            "address",
            "modalities",
            "services",
            "spaceType",
            "comments",
            "comments.images",
            "user"
        ]);
        return new SpaceResource($space);
    }

    /**
     * Store a newly created resource in storage.
     */
    //public function store(GuardarSpaceRequest $request)
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    //public function update(GuardarSpaceRequest $request, Space $space)
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    //public function destroy(Space $space)
    public function destroy(string $id)
    {
        //
    }
}
