<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MultiTenancyRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_company_and_assigns_admin_role()
    {
        // Create some permissions manually to avoid running full seeders
        Permission::create(['name' => 'view_dashboard', 'guard_name' => 'web']);
        Permission::create(['name' => 'manage_users', 'guard_name' => 'web']);

        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('signup'), $data);

        if ($response->status() !== 302) {
             dump($response->getContent());
        }

        $response->assertRedirect();
        
        // Check Company created
        $company = Company::where('email', 'test@example.com')->first();
        $this->assertNotNull($company, 'Company should be created');
        $this->assertEquals("Test User's Company", $company->name);

        // Check User created
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user, 'User should be created');
        $this->assertEquals($company->id, $user->company_id);

        // Check Admin Role created for this company
        $adminRole = Role::where('name', 'Admin')
                         ->where('company_id', $company->id)
                         ->first();
        $this->assertNotNull($adminRole, 'Admin role should be created for this company');

        // Check Role has permissions
        $this->assertTrue($adminRole->hasPermissionTo('view_dashboard'));
        $this->assertTrue($adminRole->hasPermissionTo('manage_users'));

        // Check User has Admin Role
        // Important: Set team id to verify role assignment in multi-tenancy
        setPermissionsTeamId($company->id);
        
        // Reload user to get relations
        $user = User::find($user->id);
        
        $this->assertTrue($user->hasRole('Admin'), 'User should have Admin role');
    }
}
