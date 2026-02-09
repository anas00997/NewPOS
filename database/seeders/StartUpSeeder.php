<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use App\Models\Setting;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StartUpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Default Company
        $company = \App\Models\Company::firstOrCreate(
            ['name' => 'QTec Solution'],
            ['email' => 'admin@qtecsolution.net']
        );

        $user = User::firstOrCreate(
            ['email' => 'demo@qtecsolution.net'],
            [
                'name' => 'Mr Admin',
                'password' => bcrypt('87654321'),
                'username' => uniqid(),
                'company_id' => $company->id,
            ]
        );

        Customer::firstOrCreate(
            ['phone' => '012345678'],
            [
                'name' => 'Walking Customer',
                'company_id' => $company->id,
            ]
        );

        Supplier::firstOrCreate(
            ['phone' => '012345678'],
            [
                'name' => 'Own Supplier',
                'company_id' => $company->id,
            ]
        );

        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']); // Global role
        
        // Set context to the company for role assignment
        setPermissionsTeamId($company->id);
        
        if (!$user->hasRole('Admin')) {
            $user->assignRole($role);
        }

        $this->call([
            UnitSeeder::class,
            CurrencySeeder::class,
            RolePermissionSeeder::class,
        ]);
    }
}
