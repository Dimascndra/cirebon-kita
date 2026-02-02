<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Permissions
        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'permission-list',
            'ad-list',
            'ad-create',
            'ad-edit',
            'ad-delete',

            // News
            'news-list',
            'news-create',
            'news-edit',
            'news-delete',

            // Jobs
            'job-list',
            'job-create',
            'job-edit',
            'job-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Define Roles and Assign Permissions

        // SuperAdmin: All permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'SuperAdmin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Admin: Manage Users and Roles (but maybe restricted in future)
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->givePermissionTo([
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'role-list', // Can see roles but maybe not edit them
        ]);

        // Editor
        $editorRole = Role::firstOrCreate(['name' => 'Editor']);
        // Editor would have content permissions, leaving empty for now or adding dummy

        // Company
        $companyRole = Role::firstOrCreate(['name' => 'Company']);

        // Applicant
        $applicantRole = Role::firstOrCreate(['name' => 'Applicant']);

        // 3. Create Default SuperAdmin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@cirebonkita.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        $superAdmin->assignRole($superAdminRole);

        // 4. Create Default Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@cirebonkita.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole($adminRole);

        // 5. Create Company User
        $company = User::firstOrCreate(
            ['email' => 'company@cirebonkita.com'],
            [
                'name' => 'Company Representative',
                'password' => Hash::make('password'),
            ]
        );
        $company->assignRole($companyRole);

        // 6. Create Applicant Users (Demo)
        $applicant1 = User::firstOrCreate(
            ['email' => 'applicant@cirebonkita.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
            ]
        );
        $applicant1->assignRole($applicantRole);

        $applicant2 = User::firstOrCreate(
            ['email' => 'jane@cirebonkita.com'],
            [
                'name' => 'Jane Smith',
                'password' => Hash::make('password'),
            ]
        );
        $applicant2->assignRole($applicantRole);

        $applicant3 = User::firstOrCreate(
            ['email' => 'michael@cirebonkita.com'],
            [
                'name' => 'Michael Johnson',
                'password' => Hash::make('password'),
            ]
        );
        $applicant3->assignRole($applicantRole);

        // 7. Output credentials
        $this->command->info('===========================================');
        $this->command->info('Roles and Permissions seeded!');
        $this->command->info('===========================================');
        $this->command->info('');
        $this->command->info('DEFAULT USER CREDENTIALS:');
        $this->command->info('');
        $this->command->info('📌 SUPER ADMIN:');
        $this->command->info('   Email: superadmin@cirebonkita.com');
        $this->command->info('   Pass:  password');
        $this->command->info('');
        $this->command->info('📌 ADMIN:');
        $this->command->info('   Email: admin@cirebonkita.com');
        $this->command->info('   Pass:  password');
        $this->command->info('');
        $this->command->info('📌 COMPANY:');
        $this->command->info('   Email: company@cirebonkita.com');
        $this->command->info('   Pass:  password');
        $this->command->info('');
        $this->command->info('📌 APPLICANTS:');
        $this->command->info('   Email: applicant@cirebonkita.com (John Doe)');
        $this->command->info('   Email: jane@cirebonkita.com (Jane Smith)');
        $this->command->info('   Email: michael@cirebonkita.com (Michael Johnson)');
        $this->command->info('   Pass:  password (semua)');
        $this->command->info('');
        $this->command->info('===========================================');
    }
}
