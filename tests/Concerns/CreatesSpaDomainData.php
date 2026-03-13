<?php

namespace Tests\Concerns;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

trait CreatesSpaDomainData
{
    protected function createUserWithRole(string $roleName): User
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $role = Role::findOrCreate($roleName, 'web');

        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    protected function createAdminFixture(): array
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach ([
            'user-list',
            'role-list',
            'news-list',
            'news-create',
            'news-edit',
            'news-delete',
            'job-list',
            'job-create',
            'job-edit',
            'job-delete',
            'ad-list',
        ] as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        foreach (['SuperAdmin', 'Admin', 'Company', 'Applicant'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }

        $admin = $this->createUserWithRole('SuperAdmin');
        $admin->givePermissionTo(Permission::all());

        $category = Category::create([
            'name' => 'Teknologi',
            'slug' => 'teknologi',
        ]);

        $company = Company::create([
            'name' => 'PT Cirebon Digital',
            'slug' => 'pt-cirebon-digital',
            'verified' => true,
            'industry' => 'Technology',
            'email' => 'company@example.test',
        ]);

        $post = Post::create([
            'title' => 'Artikel Uji',
            'slug' => 'artikel-uji',
            'category_id' => $category->id,
            'excerpt' => 'Ringkasan artikel uji',
            'content' => '<p>Konten artikel uji</p>',
            'status' => 'published',
            'published_at' => now(),
            'views' => 25,
        ]);

        $job = Job::create([
            'title' => 'Frontend Engineer',
            'slug' => 'frontend-engineer-' . Str::random(5),
            'company_id' => $company->id,
            'category_id' => $category->id,
            'location' => 'Cirebon',
            'salary_range' => 'Rp 8.000.000 - Rp 12.000.000',
            'type' => 'Full-time',
            'status' => 'active',
            'description' => '<p>Deskripsi lowongan</p>',
        ]);

        $ad = Ad::create([
            'title' => 'Banner Uji',
            'image' => 'ads/test-banner.jpg',
            'url' => 'https://example.test',
            'placement' => 'homepage',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'is_active' => true,
            'clicks' => 10,
            'impressions' => 200,
        ]);

        return compact('admin', 'category', 'company', 'post', 'job', 'ad');
    }

    protected function createCompanyFixture(): array
    {
        $companyUser = $this->createUserWithRole('Company');
        $applicant = $this->createUserWithRole('Applicant');

        $category = Category::create([
            'name' => 'Bisnis',
            'slug' => 'bisnis',
        ]);

        $company = Company::create([
            'name' => 'PT Hiring Cirebon',
            'slug' => 'pt-hiring-cirebon',
            'verified' => true,
            'industry' => 'Recruitment',
            'email' => 'hr@example.test',
        ]);

        $job = Job::create([
            'title' => 'Operations Staff',
            'slug' => 'operations-staff-' . Str::random(5),
            'company_id' => $company->id,
            'category_id' => $category->id,
            'location' => 'Kabupaten Cirebon',
            'salary_range' => 'Rp 4.000.000 - Rp 5.000.000',
            'type' => 'Full-time',
            'status' => 'active',
            'description' => '<p>Deskripsi operations staff</p>',
        ]);

        $application = JobApplication::create([
            'job_id' => $job->id,
            'user_id' => $applicant->id,
            'cv_path' => 'cvs/test-cv.pdf',
            'status' => 'pending',
            'cover_letter' => 'Saya tertarik melamar posisi ini.',
            'notes' => null,
        ]);

        return compact('companyUser', 'applicant', 'category', 'company', 'job', 'application');
    }
}
