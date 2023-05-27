<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class TechnologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $technologies = ['HTML', 'CSS', 'JS', 'PHP', 'MySQL', 'VS Code', 'Laravel'];

        foreach($technologies as $technology) {
            $newTech = new Technology();

            $newTech->name = $technology;
            $newTech->color = $faker->hexColor();
            $newTech->slug = Str::slug($newTech->name, '-');

            $newTech->save();
        }
    }
}
