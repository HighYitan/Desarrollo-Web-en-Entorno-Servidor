<?php

namespace Database\Seeders;

use App\Models\Tag;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Post;
use App\Models\User;
use App\Models\Image;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\CategorySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Seeders
        $this->call(UserSeeder::class); //Crea un seeder concret.
        //JSON
        $this->call(CategorySeeder::class);

        // Factories
        User::factory(5)->create();  // Crea 5 Factories aleatòris
        Category::factory(5)->create();
        $posts = Post::factory(20)->create();
        $tags = Tag::factory(10)->create();
        $comments = Comment::factory(100)->create();
        $images = Image::factory(100)->create();

        //Post_Tag::factory(5)->create();
        $posts->each(function ($post) use ($tags) {
            $post->tags()->attach(
                $tags->random(rand(1, 5))->pluck('id')->toArray()
            );
        });

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
