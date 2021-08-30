<?php

namespace Armincms\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \Armincms\Contract\Models\Admin::factory(1)->create();
    }
}
