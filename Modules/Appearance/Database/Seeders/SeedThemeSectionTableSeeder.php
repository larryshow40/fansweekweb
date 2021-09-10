<?php

namespace Modules\Appearance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Appearance\Entities\ThemeSection;
use Illuminate\Database\Eloquent\Model;
use DB;

class SeedThemeSectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ThemeSection::create([
            'label'         => 'Primary Section',
            'theme_id'      => '1',
            'type'          => '0',
            'order'         => '1',
            'post_amount'   => '1',
            'section_style' => 'style_1',
            'is_primary'    => '1',
            'status'        => '1'
        ]);

        ThemeSection::create([
            'label'         => 'latest_post',
            'theme_id'      => '1',
            'type'          => '3',
            'order'         => '6',
            'is_primary'    => '0',
            'status'        => '1'
        ]);


        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
