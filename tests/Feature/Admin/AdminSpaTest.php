<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\CreatesSpaDomainData;
use Tests\TestCase;

class AdminSpaTest extends TestCase
{
    use CreatesSpaDomainData;
    use RefreshDatabase;

    public function test_non_admin_users_are_blocked_from_admin_web_and_api_routes(): void
    {
        $nonAdmin = $this->createUserWithRole('Applicant');

        $this->actingAs($nonAdmin)
            ->get('/admin/dashboard')
            ->assertForbidden();

        Sanctum::actingAs($nonAdmin);

        $this->getJson('/api/admin/dashboard')
            ->assertForbidden();
    }

    public function test_admin_spa_routes_render_successfully(): void
    {
        $fixture = $this->createAdminFixture();
        $admin = $fixture['admin'];

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('react-app', false);

        $this->actingAs($admin)
            ->get('/admin/users')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/admin/roles')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/admin/news')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/admin/jobs')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/admin/ads')
            ->assertOk();
    }

    public function test_admin_api_endpoints_return_expected_payloads(): void
    {
        $fixture = $this->createAdminFixture();
        $admin = $fixture['admin'];
        $post = $fixture['post'];
        $job = $fixture['job'];
        $ad = $fixture['ad'];

        Sanctum::actingAs($admin);

        $this->getJson('/api/admin/dashboard')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.totalUsers', 1);

        $this->getJson('/api/admin/users')
            ->assertOk()
            ->assertJsonPath('data.users.total', 1);

        $this->getJson('/api/admin/roles')
            ->assertOk()
            ->assertJsonFragment(['name' => 'SuperAdmin']);

        $this->getJson('/api/admin/news')
            ->assertOk()
            ->assertJsonFragment(['title' => $post->title]);

        $this->getJson('/api/admin/news/meta')
            ->assertOk()
            ->assertJsonFragment(['name' => 'Teknologi']);

        $this->getJson('/api/admin/news/' . $post->id)
            ->assertOk()
            ->assertJsonPath('data.id', $post->id);

        $this->getJson('/api/admin/jobs')
            ->assertOk()
            ->assertJsonFragment(['title' => $job->title]);

        $this->getJson('/api/admin/jobs/meta')
            ->assertOk()
            ->assertJsonFragment(['name' => 'PT Cirebon Digital']);

        $this->getJson('/api/admin/jobs/' . $job->id)
            ->assertOk()
            ->assertJsonPath('data.id', $job->id);

        $this->getJson('/api/admin/ads')
            ->assertOk()
            ->assertJsonFragment(['title' => $ad->title]);

        $this->getJson('/api/admin/ads/' . $ad->id)
            ->assertOk()
            ->assertJsonPath('data.id', $ad->id);
    }

    public function test_admin_can_create_update_and_delete_users(): void
    {
        $fixture = $this->createAdminFixture();
        $admin = $fixture['admin'];

        $createResponse = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Manager Operasional',
            'email' => 'manager@example.test',
            'password' => 'password',
            'role' => 'Admin',
        ]);

        $createResponse
            ->assertOk()
            ->assertJsonFragment(['message' => 'User created successfully']);

        $userId = \App\Models\User::where('email', 'manager@example.test')->value('id');
        $this->assertNotNull($userId);

        $updateResponse = $this->actingAs($admin)->put("/admin/users/{$userId}", [
            'name' => 'Manager Regional',
            'email' => 'manager-regional@example.test',
            'password' => 'new-password',
            'role' => 'Company',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonFragment(['message' => 'User updated successfully']);

        $user = \App\Models\User::findOrFail($userId);

        $this->assertDatabaseHas('users', [
            'id' => $userId,
            'name' => 'Manager Regional',
            'email' => 'manager-regional@example.test',
        ]);
        $this->assertTrue($user->hasRole('Company'));

        $this->actingAs($admin)
            ->delete("/admin/users/{$userId}")
            ->assertOk()
            ->assertJsonFragment(['message' => 'User deleted successfully']);

        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    public function test_admin_user_crud_validates_required_fields(): void
    {
        $fixture = $this->createAdminFixture();
        $admin = $fixture['admin'];

        $this->actingAs($admin)
            ->post('/admin/users', [])
            ->assertStatus(302)
            ->assertSessionHasErrors(['name', 'email', 'password', 'role']);
    }

    public function test_admin_can_create_update_and_delete_roles(): void
    {
        $fixture = $this->createAdminFixture();
        $admin = $fixture['admin'];

        $createResponse = $this->actingAs($admin)->post('/admin/roles', [
            'name' => 'Editor',
            'permissions' => ['news-list', 'news-create'],
        ]);

        $createResponse
            ->assertOk()
            ->assertJsonFragment(['message' => 'Role created successfully']);

        $roleId = \Spatie\Permission\Models\Role::where('name', 'Editor')->value('id');
        $this->assertNotNull($roleId);

        $updateResponse = $this->actingAs($admin)->put("/admin/roles/{$roleId}", [
            'name' => 'Senior Editor',
            'permissions' => ['news-list', 'news-edit'],
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonFragment(['message' => 'Role updated successfully']);

        $role = \Spatie\Permission\Models\Role::findOrFail($roleId);

        $this->assertSame('Senior Editor', $role->name);
        $this->assertEqualsCanonicalizing(
            ['news-list', 'news-edit'],
            $role->permissions()->pluck('name')->all()
        );

        $this->actingAs($admin)
            ->delete("/admin/roles/{$roleId}")
            ->assertOk()
            ->assertJsonFragment(['message' => 'Role deleted successfully']);

        $this->assertDatabaseMissing('roles', ['id' => $roleId]);
    }

    public function test_admin_role_crud_validates_required_fields(): void
    {
        $fixture = $this->createAdminFixture();
        $admin = $fixture['admin'];

        $this->actingAs($admin)
            ->post('/admin/roles', [])
            ->assertStatus(302)
            ->assertSessionHasErrors(['name']);
    }

    public function test_admin_can_create_update_and_delete_news(): void
    {
        Storage::fake('public');

        $fixture = $this->createAdminFixture();
        $admin = $fixture['admin'];
        $category = $fixture['category'];

        $createResponse = $this->actingAs($admin)->post('/admin/news', [
            'title' => 'Artikel Baru Admin',
            'category_id' => $category->id,
            'excerpt' => 'Ringkasan artikel baru admin',
            'content' => '<p>Konten artikel baru admin</p>',
            'status' => 'published',
            'image' => UploadedFile::fake()->image('news.jpg'),
        ]);

        $createResponse
            ->assertOk()
            ->assertJsonFragment(['message' => 'News created successfully']);

        $postId = \App\Models\Post::where('slug', 'artikel-baru-admin')->value('id');
        $this->assertNotNull($postId);

        $updateResponse = $this->actingAs($admin)->post("/admin/news/{$postId}", [
            '_method' => 'PUT',
            'title' => 'Artikel Admin Diperbarui',
            'category_id' => $category->id,
            'excerpt' => 'Ringkasan diperbarui',
            'content' => '<p>Konten diperbarui</p>',
            'status' => 'draft',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonFragment(['message' => 'News updated successfully']);

        $this->assertDatabaseHas('posts', [
            'id' => $postId,
            'title' => 'Artikel Admin Diperbarui',
            'status' => 'draft',
        ]);

        $deleteResponse = $this->actingAs($admin)->delete("/admin/news/{$postId}");

        $deleteResponse
            ->assertOk()
            ->assertJsonFragment(['message' => 'News deleted successfully']);

        $this->assertDatabaseMissing('posts', [
            'id' => $postId,
        ]);
    }

    public function test_admin_can_create_update_and_delete_jobs(): void
    {
        $fixture = $this->createAdminFixture();
        $admin = $fixture['admin'];
        $company = $fixture['company'];

        $createResponse = $this->actingAs($admin)->post('/admin/jobs', [
            'title' => 'Backend Engineer',
            'company_id' => $company->id,
            'location' => 'Cirebon Kota',
            'type' => 'Contract',
            'status' => 'active',
            'description' => '<p>Deskripsi backend engineer</p>',
            'salary_range' => 'Rp 9.000.000 - Rp 11.000.000',
        ]);

        $createResponse
            ->assertOk()
            ->assertJsonFragment(['message' => 'Job created successfully']);

        $jobId = \App\Models\Job::where('slug', 'backend-engineer')->value('id');
        $this->assertNotNull($jobId);

        $updateResponse = $this->actingAs($admin)->post("/admin/jobs/{$jobId}", [
            '_method' => 'PUT',
            'title' => 'Backend Engineer Senior',
            'company_id' => $company->id,
            'location' => 'Kabupaten Cirebon',
            'type' => 'Full-time',
            'status' => 'closed',
            'description' => '<p>Deskripsi diperbarui</p>',
            'salary_range' => 'Rp 12.000.000 - Rp 15.000.000',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonFragment(['message' => 'Job updated successfully']);

        $this->assertDatabaseHas('job_vacancies', [
            'id' => $jobId,
            'title' => 'Backend Engineer Senior',
            'status' => 'closed',
        ]);

        $deleteResponse = $this->actingAs($admin)->delete("/admin/jobs/{$jobId}");

        $deleteResponse
            ->assertOk()
            ->assertJsonFragment(['message' => 'Job deleted successfully']);

        $this->assertDatabaseMissing('job_vacancies', [
            'id' => $jobId,
        ]);
    }

    public function test_admin_can_create_update_and_delete_ads(): void
    {
        Storage::fake('public');

        $fixture = $this->createAdminFixture();
        $admin = $fixture['admin'];
        $existingAd = $fixture['ad'];

        $createResponse = $this->actingAs($admin)->post('/admin/ads', [
            'title' => 'Banner Promo Baru',
            'image' => UploadedFile::fake()->image('banner.jpg'),
            'url' => 'https://promo.example.test',
            'placement' => 'sidebar',
            'start_date' => now()->subDay()->toDateTimeString(),
            'end_date' => now()->addDay()->toDateTimeString(),
            'is_active' => '1',
        ]);

        $createResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $newAdId = \App\Models\Ad::where('title', 'Banner Promo Baru')->value('id');
        $this->assertNotNull($newAdId);

        $updateResponse = $this->actingAs($admin)->post("/admin/ads/{$existingAd->id}", [
            '_method' => 'PUT',
            'title' => 'Banner Uji Diperbarui',
            'url' => 'https://updated.example.test',
            'placement' => 'footer',
            'start_date' => now()->toDateTimeString(),
            'end_date' => now()->addDays(2)->toDateTimeString(),
            'is_active' => '1',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('ads', [
            'id' => $existingAd->id,
            'title' => 'Banner Uji Diperbarui',
            'placement' => 'footer',
        ]);

        $deleteResponse = $this->actingAs($admin)->delete("/admin/ads/{$newAdId}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('ads', [
            'id' => $newAdId,
        ]);
    }

    public function test_admin_content_crud_validates_required_fields(): void
    {
        Storage::fake('public');

        $fixture = $this->createAdminFixture();
        $admin = $fixture['admin'];

        $this->actingAs($admin)
            ->post('/admin/news', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'category_id', 'content', 'status']);

        $this->actingAs($admin)
            ->post('/admin/jobs', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'company_id', 'location', 'type', 'status', 'description']);

        $this->actingAs($admin)
            ->post('/admin/ads', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'image', 'placement']);
    }
}
