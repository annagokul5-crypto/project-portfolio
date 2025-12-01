<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PortfolioSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Admin User
        DB::table('whatsapp_admins')->insert([
            'phone_number' => '+919790168632',
            'username' => 'admin',
            'password_hash' => Hash::make('admin123'),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Insert Hero Content
        DB::table('hero_content')->insert([
            'name' => 'GOKULRAJU A',
            'title' => 'ASPIRING SOFTWARE DEVELOPER',
            'objective' => 'Enthusiastic AI and Data Science student passionate about SaaS development and real-world innovation.',
            'resume_path' => 'resumes/Gokulraju-resume.pdf',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Insert About Content
        DB::table('about_content')->insert([
            'content' => "I'm Gokulraju A, a B.Tech student specializing in Artificial Intelligence & Data Science at Kangeyam Institute of Technology.",
            'image_path' => 'images/real1.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Insert Skill Categories
        DB::table('skill_categories')->insert([
            ['name' => 'Programming', 'order_index' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Frameworks', 'order_index' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Web Technologies', 'order_index' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tools', 'order_index' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 5. Insert Skills
        DB::table('skills')->insert([
            ['category_id' => 1, 'skill_name' => 'Python', 'percentage' => 80, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'skill_name' => 'JavaScript', 'percentage' => 85, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'skill_name' => 'Laravel', 'percentage' => 75, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'skill_name' => 'Flutter', 'percentage' => 70, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'skill_name' => 'HTML', 'percentage' => 90, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'skill_name' => 'CSS', 'percentage' => 85, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 4, 'skill_name' => 'Git', 'percentage' => 80, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 6. Insert Projects
        DB::table('projects')->insert([
            [
                'title' => 'E-Commerce Web Application',
                'description' => 'Complete online shopping platform',
                'tools' => 'HTML, CSS, JavaScript, Laravel, MySQL',
                'status' => 'ongoing',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'KIT Connect',
                'description' => 'Smart dialer application',
                'tools' => 'Dart, Flutter, Firebase',
                'status' => 'ongoing',
                'order_index' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // 7. Insert Contact Info
        DB::table('contact_info')->insert([
            'phone' => '9790168632',
            'email' => 'annagokul5@gmail.com',
            'whatsapp' => '+919790168632',
            'location' => 'Erode, Tamil Nadu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        echo "✅ Portfolio data seeded successfully!\n";
    }
}
