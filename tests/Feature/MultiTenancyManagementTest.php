<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MultiTenancyManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $company;
    protected $adminUser;
    protected $adminRole;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup permissions
        Permission::create(['name' => 'user_view', 'guard_name' => 'web']);
        Permission::create(['name' => 'user_create', 'guard_name' => 'web']);
        Permission::create(['name' => 'role_view', 'guard_name' => 'web']);
        Permission::create(['name' => 'role_create', 'guard_name' => 'web']);
        Permission::create(['name' => 'role_delete', 'guard_name' => 'web']);

        // Setup Company and Admin
        $this->company = Company::create(['name' => 'Test Company', 'email' => 'company@test.com']);
        
        $this->adminUser = User::create([
            'name' => 'Company Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'username' => 'admin_test',
            'company_id' => $this->company->id
        ]);

        $this->adminRole = Role::create([
            'name' => 'Admin',
            'guard_name' => 'web',
            'company_id' => $this->company->id
        ]);
        
        $this->adminRole->syncPermissions(Permission::all());

        // Set global team id for role assignment
        setPermissionsTeamId($this->company->id);
        $this->adminUser->assignRole($this->adminRole);
    }

    public function test_admin_can_create_role_in_company_scope()
    {
        $this->actingAs($this->adminUser);
        
        // Verify we are starting with 1 role
        $this->assertEquals(1, Role::count());

        $response = $this->post(route('backend.admin.roles.create'), [
            'name' => 'Manager'
        ]);

        $response->assertRedirect();
        
        // Verify role created
        $this->assertEquals(2, Role::count());
        $managerRole = Role::where('name', 'Manager')->first();
        $this->assertNotNull($managerRole);
        $this->assertEquals($this->company->id, $managerRole->company_id);
    }

    public function test_admin_can_create_user_in_company_scope()
    {
        $this->actingAs($this->adminUser);

        // Create a role to assign to new user
        $role = Role::create(['name' => 'Staff', 'company_id' => $this->company->id]);

        $response = $this->post(route('backend.admin.user.create'), [
            'name' => 'New Employee',
            'email' => 'employee@test.com',
            'password' => 'password',
            'role' => $role->id
        ]);
        
        if ($response->status() !== 302) {
             dump($response->getContent());
        }

        $response->assertRedirect();

        // Verify user created
        $employee = User::where('email', 'employee@test.com')->first();
        $this->assertNotNull($employee);
        $this->assertEquals($this->company->id, $employee->company_id);
        
        // Verify role assignment
        // Note: The middleware sets the team id for the request, but in test we might need to be careful
        // The controller uses syncRoles($role). Role model has company_id.
        // Let's verify relation
        setPermissionsTeamId($this->company->id);
        $this->assertTrue($employee->hasRole('Staff'));
    }

    public function test_admin_cannot_delete_admin_role()
    {
        $this->actingAs($this->adminUser);
        
        $response = $this->get(route('backend.admin.roles.delete', $this->adminRole->id));
        
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Cannot delete Admin role');
        
        $this->assertDatabaseHas('roles', ['id' => $this->adminRole->id]);
    }

    public function test_scope_isolation()
    {
        // Create another company
        $otherCompany = Company::create(['name' => 'Other Company', 'email' => 'other@test.com']);
        $otherUser = User::create([
            'name' => 'Other Admin',
            'email' => 'other@test.com',
            'password' => bcrypt('password'),
            'username' => 'other_admin',
            'company_id' => $otherCompany->id
        ]);
        
        // Create a role in other company
        $otherRole = Role::create(['name' => 'Other Role', 'company_id' => $otherCompany->id]);

        // Login as first company admin
        $this->actingAs($this->adminUser);

        // Should not see other company's role
        $this->assertEquals(1, Role::count()); // "Admin" of this company only (and not 'Other Role')
        // Wait, Role::count() applies global scope? Yes, BelongsToCompany applies global scope if auth check passes.
        // In test actingAs sets auth.
        
        // Verify direct database query vs scoped query
        // Without scope (using raw DB or without auth)
        auth()->logout();
        $this->assertEquals(2, \Illuminate\Support\Facades\DB::table('roles')->count());
        
        // With scope
        $this->actingAs($this->adminUser);
        $this->assertEquals(1, Role::count());
        $this->assertEquals('Admin', Role::first()->name);
    }
}
