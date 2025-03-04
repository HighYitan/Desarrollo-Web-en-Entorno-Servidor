<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActualizarSpaceRequest;
use App\Http\Requests\GuardarSpaceRequest;
use App\Models\Address;
use App\Models\Modality;
use App\Models\Service;
use App\Models\Space;
use App\Models\SpaceType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Validator;

class SpaceControllerCRUD extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$spaces = Space::all(); // Donde Post es la class de la tabla posts all() es obtener todos los registros
        //$spaces = Space::find(1); // Busca registro con la PK = 1 
        //$spaces = Space::find([1, 3]); // Busca registro con la PK = 1, PK = 3
        //$spaces = Space::where('accessType','=','n')->get(); // Por defecto el where toma el '=', no hacía fala ponerlo, es redundante
        //$spaces = Space::where('accessType', 'n')->where('id','>',2)->get(); // Where (posted = not) AND (id > 2); solo pilla los que tienen ese accessType y id > 2
        //$spaces = Space::where('accessType', 'n')->orWhere('id','>',2)->get(); // Where (posted = not) OR (id > 2), no pillará el id 1 y 2
        /*$spaces = Space::where('accessType', 's')
        ->orwhere(function($query) {
            $query->where('accessType', 'n')
            ->where('space_type_id','2');
        })->get();^*/
        //$spaces = Space::where('accessType', 'n')->where('id','>',2)->first(); // Where (posted = not) OR (id > 2) y solo el primero
        //$spaces = Space::where('accessType', 'n')->orderBy('id','desc')->get(); // Ordenado de forma descendente
        //$spaces = Space::select('name','regNumber','observation_CA')->get(); // Extracción de columnas específicas 
        //$spaces = Space::pluck('name','regNumber','observation_CA'); // Simplifica la salida, solamente los valores (Creo que es clave->valor a lo map)
        //$spaces = Space::take(10)->skip(10)->get(); // De la 10 a la 20, es para paginar la salidad de la SELECT

        //dd($spaces); // volcado del resultado
        $spaces = Space::withCount(['comments as puntuacióMitjana' => function ($query) {
            $query->select(DB::raw('coalesce(avg(score), 0)'))->where('status', 'Y');
        }])
        ->orderBy('updated_at', 'DESC')
        ->paginate(3);

        //$spaces = Space::orderBy('updated_at','DESC')->paginate(3); // Obtención publicaciones orden fecha creación y paginación
        return view('space.index',['spaces' => $spaces]);  // Llamada a la View pasando $posts para maquetar el resultado del SQL
    }

    public function indexDestacado()
    {
        $spaces = Space::withCount(['comments as puntuacióMitjana' => function ($query) {
            $query->select(DB::raw('coalesce(avg(score), 0)'))->where('status', 'Y');
        }])
        ->orderBy('puntuacióMitjana', 'DESC')
        ->orderBy('updated_at', 'DESC')
        ->paginate(3);
        //$spaces = Space::orderBy('updated_at','DESC')->paginate(3); // Obtención publicaciones orden fecha creación y paginación
        return view('space.index',['spaces' => $spaces]);  // Llamada a la View pasando $posts para maquetar el resultado del SQL
    }
    
    /*public function importJsonForm()
    {
        return view('space.import');
    }

    public function importJson(Request $request)
    {
        $request->validate([
            'json_text' => 'required|string',
        ]);
    
        $jsonContent = $request->input('json_text');
        $spacesData = json_decode($jsonContent, true);
    
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['json_text' => 'Invalid JSON data.']);
        }
    
        $errors = [];
        foreach ($spacesData as $index => $spaceData) {
            $validator = Validator::make($spaceData, (new GuardarSpaceRequest())->rules());
            //dd(array_column($spaceData['modality_id'], 'id'));
            if ($validator->fails()) {
                dd($validator->errors()->all());
                $errors[$index] = $validator->errors()->all();
                continue;
            }
    
            $space = Space::create([
                "name" => $spaceData['name'],
                "regNumber" => $spaceData['regNumber'],
                "observation_CA" => $spaceData['observation_CA'],
                "observation_ES" => $spaceData['observation_ES'],
                "observation_EN" => $spaceData['observation_EN'],
                "email" => $spaceData['email'],
                "phone" => $spaceData['phone'],
                "website" => $spaceData['website'],
                "accessType" => $spaceData['accessType'],
                "address_id" => $spaceData['address']['id'],
                "space_type_id" => $spaceData['space_type']['id'],
                "user_id" => Auth::user()->id,
            ]);
    
            if (isset($spaceData['modality_id'])) {
                $modalities = $spaceData['modality_id']['id'];
                $space->modalities()->attach($modalities, ["created_at" => now(), "updated_at" => now()]);
            }
    
            if (isset($spaceData['service_id'])) {
                $services = $spaceData['service_id']['id'];
                $space->services()->attach($services, ["created_at" => now(), "updated_at" => now()]);
            }
        }
    
        if (!empty($errors)) {
            dd($errors);
            return back()->withErrors($errors);
        }
    
        return redirect()->route('spaceCRUD.index')->with('status', 'Spaces imported successfully.');
    }*/

    public function exportJson()
    {
        // Make a request to your API endpoint with the API key
        /*$response = Http::withHeaders([
            'Authorization' => 'Bearer p7J4H1G2kLzT9fDxXy3mK8Qc6nA0Wr5vBLpYv7R'
        ])->get('http://baleart.test/api/space');

        // Check if the request was successful
        if ($response->successful()) {
            $spaces = $response->json();

            // Convert the data to a JSON string with pretty print and unescaped Unicode
            $jsonContent = json_encode($spaces, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            $fileName = 'spaces.json';
            $filePath = storage_path($fileName);

            // Write the JSON content to a file
            file_put_contents($filePath, $jsonContent);

            // Return a response to download the JSON file
            return response()->download($filePath, $fileName, [
                'Content-Type' => 'application/json; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ])->deleteFileAfterSend(true);
        } else {
            // Handle the error if the request was not successful
            return response()->json(['error' => 'Failed to fetch data from API'], 500);
        }*/
        $spaces = Space::with(['spaceType', 'modalities', 'services', 'address', 'comments' => function ($query) {
            $query->where('status', 'Y');
        }])->get();

        $spaces->each(function ($space) {
            $space->puntuacióMitjana = $space->comments->avg('score');
        });

        $jsonContent = $spaces->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $fileName = 'spaces.json';
        $filePath = storage_path($fileName);

        file_put_contents($filePath, $jsonContent);

        return response()->download($filePath, $fileName, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $spaceTypes = SpaceType::pluck("name", "id"); // Recuperamos los tipos de espacios para asignarlas en el create
        $modalities = Modality::pluck("name", "id"); // Recuperamos las modalidades para asignarlas en el create
        $services   = Service::pluck("name", "id"); // Recuperamos los servicios para asignarlas en el create
        $addresses  = Address::pluck("name", "id"); // Recuperamos las direcciones para asignarlas en el create

        return view('space.create',
            ['spaceTypes' => $spaceTypes,
            'modalities' => $modalities,
            'services' => $services,
            'addresses' => $addresses]
        ); // Llama a la vista create.blade.php con los datos de los tipos de espacios, modalidades, servicios y direcciones
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GuardarSpaceRequest $request)
    {
        $espaiEloquent = Space::create([
            "name" => $request->name,
            "regNumber" => $request->regNumber,
            "observation_CA" => $request->observation_CA,
            "observation_ES" => $request->observation_ES,
            "observation_EN" => $request->observation_EN,
            "email" => $request->email,
            "phone" => $request->phone,
            "website" => $request->website,
            "accessType" => $request->accessType, //Extraigo el primer carácter del string porque la columna solo puede contener 1 carácter.
            "address_id" => $request->address_id,
            "space_type_id" => $request->space_type_id, //Busca el id del tipo de espacio en la tabla SpaceType y lo asigna al espai en la columna space_type_id
            "user_id" => Auth::user()->id //Asigna el id del usuario que ha creado el espai en la columna user_id
        ]);

        $espaiEloquent->modalities()->attach(
            $request->modality_id, ["created_at" => now(), "updated_at" => now()]
        );
        $espaiEloquent->services()->attach(
            $request->service_id, ["created_at" => now(), "updated_at" => now()]
        );

        return redirect()->route('spaceCRUD.index'); // Redirige a la página de inicio
    }

    /**
     * Display the specified resource.
     */
    public function show(Space $spaceCRUD)
    {
        //$space = Space::find($id); // Extrae registro con PK = id
        //$space = Space::findorfail($id); // Genera una respuesta http de error en caso de not found. Un 404
        
        //return view('space.show', ['space' => $space]); // // Recordar crear la vista 


        $puntuacióMitjana = 0; // Si sigue null ni lo muestres en la card
        if ($spaceCRUD->countScore > 0) {
            $puntuacióMitjana = number_format($spaceCRUD->totalScore / $spaceCRUD->countScore, 2);
        }

        $comments = $spaceCRUD->comments()->orderBy('updated_at', 'DESC')->paginate(3); // Obtenemos los comentarios del espacio y los ordenamos por updated_at DESC

        return view('space.show',['space' => $spaceCRUD, 'puntuacióMitjana' => $puntuacióMitjana, 'comments' => $comments]);  // Porque el nombre del parámetro es así, spaceCRUD/{spaceCRUD}  
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Space $spaceCRUD)
    {
        $spaceTypes = SpaceType::pluck("name", "id"); // Recuperamos los tipos de espacios para asignarlas en el create
        $modalities = Modality::pluck("name", "id"); // Recuperamos las modalidades para asignarlas en el create
        $services   = Service::pluck("name", "id"); // Recuperamos los servicios para asignarlas en el create
        $addresses  = Address::pluck("name", "id"); // Recuperamos las direcciones para asignarlas en el create
        $puntuacióMitjana = null; // Si sigue null ni lo muestres en la card
        if ($spaceCRUD->countScore > 0) {
            $puntuacióMitjana = number_format($spaceCRUD->totalScore / $spaceCRUD->countScore, 2);
        }
        return view('space.edit',
            ['space' => $spaceCRUD,
            'spaceTypes' => $spaceTypes,
            'modalities' => $modalities,
            'services' => $services,
            'addresses' => $addresses,
            'puntuacióMitjana' => $puntuacióMitjana]
        ); // Llama a la vista edit.blade.php con los datos de los tipos de espacios, modalidades, servicios y direcciones
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActualizarSpaceRequest $request, Space $spaceCRUD)
    {
        $puntuacióMitjana = null;
        if ($spaceCRUD->countScore > 0 && $request->puntuacióMitjana !== null) {
            $puntuacióMitjana = number_format($spaceCRUD->totalScore / $spaceCRUD->countScore, 2); // Calcula la media solo si countScore es mayor que 0
            if($puntuacióMitjana !== $request->puntuacióMitjana){ // Si la puntuación media ha cambiado
                if($puntuacióMitjana >= 0 && $puntuacióMitjana <= 5){ // Si la puntuación media está en el rango
                    foreach ($spaceCRUD->comments as $comment) { // Recorre todos los comentarios del espacio
                        if($comment->status === 'Y'){ // Si el comentario está aceptado
                            $comment->score = $request->puntuacióMitjana; // Asigna la puntuación media a todos los comentarios
                            $comment->save();
                        }
                    }
                    // puntuacióMitjana de 4 o más = destacado
                    $spaceCRUD->calculateScores(); // Recalcula la puntuación total y el número de puntuaciones
                }
            }
        }

        // Sync modalities
        if ($request->has('modality_id')) {
            $spaceCRUD->modalities()->sync($request->modality_id);
        } else {
            $spaceCRUD->modalities()->sync([]);
        }

        // Sync services
        if ($request->has('service_id')) {
            $spaceCRUD->services()->sync($request->service_id);
        } else {
            $spaceCRUD->services()->sync([]);
        }

        $request = [ // Crea un array amb les dades del request mapejades amb els camps de la taula
            "name" => $request->name,
            "regNumber" => $request->regNumber,
            "observation_CA" => $request->observation_CA,
            "observation_ES" => $request->observation_ES,
            "observation_EN" => $request->observation_EN,
            "email" => $request->email,
            "phone" => $request->phone,
            "website" => $request->website,
            "accessType" => $request->accessType, //Extraigo el primer carácter del string porque la columna solo puede contener 1 carácter.
            "address_id" => $request->address_id,
            "space_type_id" => $request->space_type_id, //Busca el id del tipo de espacio en la tabla SpaceType y lo asigna al espai en la columna space_type_id
        ];

        $spaceCRUD->update($request); //Actualizamos el registro de la DDBB

        return redirect()->route('spaceCRUD.index'); // Redirige a la página de inicio
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Space $spaceCRUD)
    {
        foreach ($spaceCRUD->comments as $comment) { // Recorre los comentarios del espacio
            $comment->images()->delete(); // Borra las imágenes del comentario
            $comment->delete(); // Borra el comentario
        }

        $spaceCRUD->modalities()->detach(); // Eliminación de las relaciones N:M
        $spaceCRUD->services()->detach(); // Eliminación de las relaciones N:M

        $spaceCRUD->delete(); // Eliminación del registro 
        return redirect()->route('spaceCRUD.index'); // Redirige a la página de inicio
    }
}
