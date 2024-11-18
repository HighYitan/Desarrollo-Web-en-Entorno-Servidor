<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = new Category();
        $category->title = "Noves tecnologies";
        $category->url_clean = "noves_tecnologies";
        $category->save();

        //Des d'un arxiu
        $jsonData = file_get_contents("C:\\temp\\blog\\categories.json");
        $categories = json_decode($jsonData, true);

        //Insertar cada registro en la tabla
        foreach($categories["categories"]["categoria"] as $category){
            Category::create([
                "title" => $category["title"],
                "url_clean" => $category["url_clean"]
            ]);
        }
    }
}
