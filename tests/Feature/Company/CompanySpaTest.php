<?php

namespace Tests\Feature\Company;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\CreatesSpaDomainData;
use Tests\TestCase;

class CompanySpaTest extends TestCase
{
    use CreatesSpaDomainData;
    use RefreshDatabase;

    public function test_non_company_users_are_blocked_from_company_web_and_api_routes(): void
    {
        $nonCompanyUser = $this->createUserWithRole('Applicant');

        $this->actingAs($nonCompanyUser)
            ->get('/company/dashboard')
            ->assertForbidden();

        Sanctum::actingAs($nonCompanyUser);

        $this->getJson('/api/company/dashboard')
            ->assertForbidden();
    }

    public function test_company_spa_routes_render_successfully(): void
    {
        $fixture = $this->createCompanyFixture();
        $companyUser = $fixture['companyUser'];
        $application = $fixture['application'];

        $this->actingAs($companyUser)
            ->get('/company/dashboard')
            ->assertOk()
            ->assertSee('react-app', false);

        $this->actingAs($companyUser)
            ->get('/company/applicants')
            ->assertOk();

        $this->actingAs($companyUser)
            ->get('/company/applicants/' . $application->id)
            ->assertOk();
    }

    public function test_company_api_endpoints_return_and_update_expected_payloads(): void
    {
        $fixture = $this->createCompanyFixture();
        $companyUser = $fixture['companyUser'];
        $application = $fixture['application'];

        Sanctum::actingAs($companyUser);

        $this->getJson('/api/company/dashboard')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.totalJobs', 1);

        $this->getJson('/api/company/applicants')
            ->assertOk()
            ->assertJsonPath('data.total', 1)
            ->assertJsonFragment(['name' => $fixture['applicant']->name]);

        $this->getJson('/api/company/applicants/' . $application->id)
            ->assertOk()
            ->assertJsonPath('data.id', $application->id);

        $this->patchJson('/api/company/applicants/' . $application->id . '/status', [
            'status' => 'reviewing',
            'notes' => 'Profil kandidat sedang direview.',
        ])
            ->assertOk()
            ->assertJsonPath('data.status', 'reviewing')
            ->assertJsonPath('data.notes', 'Profil kandidat sedang direview.');

        $this->assertDatabaseHas('job_applications', [
            'id' => $application->id,
            'status' => 'reviewing',
            'notes' => 'Profil kandidat sedang direview.',
        ]);
    }

    public function test_company_applicant_status_update_validates_payload(): void
    {
        $fixture = $this->createCompanyFixture();
        $companyUser = $fixture['companyUser'];
        $application = $fixture['application'];

        Sanctum::actingAs($companyUser);

        $this->patchJson('/api/company/applicants/' . $application->id . '/status', [
            'status' => 'archived',
            'notes' => str_repeat('a', 1001),
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['status', 'notes']);
    }
}
