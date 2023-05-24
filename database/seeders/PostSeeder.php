<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for($i = 0; $i < 10; $i++) {
            $newpost = new Post();

            $newpost->title = $faker->sentence(3);
            $newpost->content = $faker->text(300);
            $newpost->slug = Str::slug($newpost->title, '-');

            $newpost->save();
        }
    }
}
