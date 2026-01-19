<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('skill_categories')->insertOrIgnore([
            'id' => 1,
            'name' => 'General',
            'order_index' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
