<?php

namespace Modules\Post\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Post\Entities\Category;

class SeedCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'category_name' => 'World' ,
            'language'      => 'en',
            'slug'          => 'world',
            'is_featured'   => '1',
            'order'         => '1'
        ]);

        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
