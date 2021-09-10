<?php

namespace Modules\Post\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Post\Entities\SubCategory;

class SeedSubCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubCategory::create([
            'sub_category_name' => 'Bangladesh',
            'slug'              => 'politics',
            'category_id'       => '1',
            'language'          => 'en',
        ]);

        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
