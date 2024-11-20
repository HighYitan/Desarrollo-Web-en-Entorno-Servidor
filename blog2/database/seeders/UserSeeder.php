<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        foreach
        $kekw = where->first()->id;
        $kekw->kekw2s()->attach($kekw2->id);
        JSON
        $jsonData = file_get_contents('C://database/data/users.json');
        $municipalities = json_decode($jsonData, true);

        foreach($municipalities["municipalities"]["municipality"] as $municipality){
            $municipality = new Municipality();
            $municipality->name = $municipality['name'];
            $municipality->save();
        }
        */
        //
        $user = new User();
        $user->name = "admin";
        $user->email = "admin@abc.com";
        $user->password = Hash::make('12345678');
        $user->role = "admin"; //$user->role_id = Role::where('name', 'admin')->value("id"); //Devuelve la id del rol de admin
        $user->save();
    }
}
//$user->role_id = Role::where('name', 'admin')->first()->id; //Devuelve la id del rol de admin
