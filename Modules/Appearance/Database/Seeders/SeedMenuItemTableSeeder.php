<?php

namespace Modules\Appearance\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Appearance\Entities\MenuItem;
use DB;

class SeedMenuItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement("INSERT INTO `menu_item` (`id`, `label`, `language`, `menu_id`, `is_mega_menu`, `order`, `parent`, `source`, `url`, `page_id`, `category_id`, `sub_category_id`, `post_id`, `status`, `new_teb`, `created_at`, `updated_at`) VALUES
        (1, 'Home', 'en', 1, 'no', 1, NULL, 'custom', '#', NULL, NULL, NULL, NULL, 1, 0, '2020-10-14 11:26:41', '2020-12-19 03:45:20'),
        (2, 'Contact Us', 'en', 1, 'no', 11, NULL, 'page', NULL, 1, NULL, NULL, NULL, 1, 0, '2020-10-14 11:34:07', '2020-12-19 10:35:31'),
        (3, 'About us', 'en', 1, 'no', 12, NULL, 'page', NULL, 2, NULL, NULL, NULL, 1, 0, '2020-10-14 11:42:29', '2020-12-19 03:45:40')");

        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
