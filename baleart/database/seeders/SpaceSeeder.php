<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Zone;
use App\Models\Space;
use App\Models\Address;
use App\Models\Service;
use App\Models\Modality;
use App\Models\SpaceType;
use Illuminate\Support\Str;
use App\Models\Municipality;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = file_get_contents("C:\\temp\\baleart\\espais.json"); //Obtiene el contenido del archivo JSON
        $espais = json_decode($jsonData, true); //Convierte el JSON en un array

        foreach($espais as $espai){ //Recorre cada espai
            Address::create([ //Decido crear los address en el seeder de espais porque así ahorro leer el mismo JSON dos veces aumentando así la eficiencia.
                "name" => $espai["adreca"],
                "municipality_id" => Municipality::where("name", $espai["municipi"])->value("id"), //Busca el id del municipio en la tabla Municipality y lo asigna al address en la columna municipality_id
                "zone_id" => Zone::where("name", $espai["zona"])->value("id") //Busca el id de la zona en la tabla Zone y lo asigna al address en la columna zone_id
            ]);
            if(User::where("email", $espai["gestor"])->exists()){
                $userId = User::where("email", $espai["gestor"])->value("id");
            }
            else{ //Si el email del gestor que ha creado el espai no existe, se asigna al administrador como dueño del espai
                $userId = User::where("role_id", 1)->value("id");
            }
            Space::create([
                "name" => $espai["nom"],
                "regNumber" => $espai["registre"],
                "observation_CA" => $espai["descripcions/cat"],
                "observation_ES" => $espai["descripcions/esp"],
                "observation_EN" => $espai["descripcions/eng"],
                "email" => $espai["email"],
                "phone" => $espai["telefon"],
                "website" => $espai["web"],
                "accessType" => Str::charAt($espai["accessibilitat"], 0), //Extraigo el primer carácter del string porque la columna solo puede contener 1 carácter.
                /*"totalScore" => 0, //He añadido default(0) al Model de Space
                "countScore" => 0,*/
                "address_id" => Address::where("name", $espai["adreca"])->value("id"), //Busca el id del address en la tabla Address y lo asigna al espai en la columna address_id
                "space_type_id" => SpaceType::where("name", $espai["tipus"])->value("id"), //Busca el id del tipo de espacio en la tabla SpaceType y lo asigna al espai en la columna space_type_id
                "user_id" => $userId //Asigna el id del usuario que ha creado el espai en la columna user_id
            ]);
            $espaiEloquent = Space::where("regNumber", $espai["registre"])->first(); //Busca el espai que acabo de crear por número de registro único.

            $modalitats = explode(",", $espai["modalitats"]); //Convierte el string de modalidades en un array de modalidades separados por comas.
            $modalitats = array_map('trim', $modalitats); //Elimina los espacios en blanco de cada modalidad para que se añadan correctamente a la tabla pivot.
            $modalitatIds = [];
            foreach($modalitats as $modalitat){ //Recorre cada modalidad del array
                $modalitatId = Modality::where("name", $modalitat)->value("id"); //Busca el id de la modalidad en la tabla Modality
                if($modalitatId){ //Comprueba si la modalidad existe y no es null
                    $modalitatIds[] = $modalitatId; //Lo añade al array modalitatIds
                }
            }
            $espaiEloquent->modalities()->attach( //Añade las modalidades al espai en la tabla pivot con el método modalities del modelo Space
                $modalitatIds, ["created_at" => now(), "updated_at" => now()] //Añade la fecha de creación y modificación también.
            );
            $serveis = explode(",", $espai["serveis"]); //Convierte el string de servicios en un array de servicios separados por comas.
            $serveis = array_map('trim', $serveis); //Elimina los espacios en blanco de cada servicio para que se añadan correctamente a la tabla pivot.
            $serveiIds = [];
            foreach($serveis as $servei){ //Recorre cada servicio del array
                $serveiId = Service::where("name", $servei)->value("id"); //Busca el id del servicio en la tabla Service
                if($serveiId){ //Comprueba si el servicio existe y no es null
                    $serveiIds[] = $serveiId; //Lo añade al array serveiIds
                }
            }
            $espaiEloquent->services()->attach( //Añade los servicios al espai en la tabla pivot con el método services del modelo Space
                $serveiIds, ["created_at" => now(), "updated_at" => now()] //Añade la fecha de creación y modificación también.
            );
        }
    }
}
