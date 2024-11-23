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
            Address::create([
                "name" => $espai["adreca"],
                "municipality_id" => Municipality::where("name", $espai["municipi"])->value("id"),
                "zone_id" => Zone::where("name", $espai["zona"])->value("id")
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
                "accessType" => Str::charAt($espai["accessibilitat"], 0),
                /*"totalScore" => 0, //He añadido default(0) al Model de Space
                "countScore" => 0,*/
                "address_id" => Address::where("name", $espai["adreca"])->value("id"),
                "space_type_id" => SpaceType::where("name", $espai["tipus"])->value("id"),
                "user_id" => $userId
                /*if(User::where("email", $espai["gestor"])->exists()){
                    "user_id" => User::where("email", $espai["gestor"])->value("id");
                }
                else{ //Si el email del gestor que ha creado el espai no existe, se asigna al administrador como dueño del espai
                    "user_id" => User::where("role_id", 1)->value("id");
                }*/
            ]);
            $espaiEloquent = Space::where("regNumber", $espai["registre"])->first();

            $modalitats = explode(",", $espai["modalitats"]);
            $modalitats = array_map('trim', $modalitats);
            $modalitatIds = [];
            foreach($modalitats as $modalitat){
                $modalitatIds = Modality::where("name", $modalitat)->value("id");
            }
            $espaiEloquent->modalities()->attach(
                $modalitatIds, ["created_at" => now(), "updated_at" => now()]
            );
            $serveis = explode(",", $espai["serveis"]);
            $serveis = array_map('trim', $serveis);
            $serveiIds = [];
            foreach($serveis as $servei){
                $serveiIds = Service::where("name", $servei)->value("id");
            }
            $espaiEloquent->services()->attach(
                $serveiIds, ["created_at" => now(), "updated_at" => now()]
            );
        }
    }
}
