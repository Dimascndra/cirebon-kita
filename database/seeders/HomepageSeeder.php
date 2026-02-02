<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Company;
use App\Models\Post;
use App\Models\Job;
use App\Models\Ad;
use Carbon\Carbon;

class HomepageSeeder extends Seeder
{
    public function run()
    {
        // Categories
        $categories = ['Teknologi', 'Bisnis', 'Kesehatan', 'Wisata', 'Kuliner'];
        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat,
                'slug' => Str::slug($cat)
            ]);
        }

        // Companies
        $companies = [
            ['name' => 'Pertamina', 'verified' => true],
            ['name' => 'Telkom Indonesia', 'verified' => true],
            ['name' => 'Gojek Cirebon', 'verified' => true],
            ['name' => 'Bakso Mang Uka', 'verified' => false],
            ['name' => 'Batik Trusmi', 'verified' => true],
        ];

        foreach ($companies as $comp) {
            Company::create([
                'name' => $comp['name'],
                'slug' => Str::slug($comp['name']),
                'verified' => $comp['verified'],
                'logo' => null // Will use placeholder in JS
            ]);
        }

        // Posts
        Post::create([
            'title' => 'Cirebon Jadi Kota Smart City Terbaik 2026',
            'slug' => 'cirebon-smart-city-2026',
            'excerpt' => 'Dengan implementasi teknologi AI dan IoT, Cirebon berhasil menyabet penghargaan kota cerdas.',
            'content' => 'Lorem ipsum...',
            'image' => null,
            'status' => 'published',
            'published_at' => Carbon::now(),
            'category_id' => 1
        ]);

        for ($i = 1; $i <= 5; $i++) {
            Post::create([
                'title' => 'Berita Lokal Update #' . $i,
                'slug' => 'berita-lokal-' . $i,
                'excerpt' => 'Ini adalah cuplikan berita lokal yang sangat menarik untuk dibaca warga Cirebon.',
                'status' => 'published',
                'published_at' => Carbon::now()->subHours($i),
                'category_id' => rand(1, 5)
            ]);
        }

        // Jobs
        $companyIds = Company::pluck('id')->toArray();
        $jobTitles = ['Staff Admin', 'Digital Marketer', 'Web Developer', 'Sales Motor', 'Barista'];

        foreach ($jobTitles as $title) {
            Job::create([
                'company_id' => $companyIds[array_rand($companyIds)],
                'category_id' => rand(1, 5),
                'title' => $title,
                'slug' => Str::slug($title . '-' . Str::random(5)),
                'location' => 'Kota Cirebon',
                'salary_range' => 'IDR 3jt - 5jt',
                'type' => 'Full Time',
                'status' => 'active',
                'description' => 'Dibutuhkan segera...',
            ]);
        }

        // Ads
        Ad::create([
            'title' => 'Promo Diskon 50%',
            'image' => '',
            'link' => 'https://google.com',
            'position' => 'top',
            'start_date' => Carbon::now()->subDay(),
            'end_date' => Carbon::now()->addDays(7),
            'status' => 'active'
        ]);

        Ad::create([
            'title' => 'Iklan Sidebar',
            'image' => '',
            'link' => '#',
            'position' => 'sidebar',
            'start_date' => Carbon::now()->subDay(),
            'end_date' => Carbon::now()->addDays(30),
            'status' => 'active'
        ]);
    }
}
