<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Image;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ZoneSeeder;
use Database\Seeders\SpaceSeeder;
use Database\Seeders\IslandSeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\ModalitySeeder;
use Database\Seeders\SpaceTypeSeeder;
use Database\Seeders\TranslationSeeder;
use Database\Seeders\MunicipalitySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Seeders
        $this->call(IslandSeeder::class); //Crea un seeder concret.

        //JSON
        $this->call(MunicipalitySeeder::class);
        $this->call(ZoneSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(SpaceTypeSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(ModalitySeeder::class);
        $this->call(SpaceSeeder::class);
        $this->call(CommentSeeder::class);
        $this->call(TranslationSeeder::class);

        // Factories
        User::factory(5)->create();  // Crea 5 User Factories aleatòris
        Image::factory(5)->create();
    }
}
