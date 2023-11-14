<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Tenant::checkCurrent()
            ? $this->runTenantSpecificSeeders()
            : $this->runLandlordSpecificSeeders();
    }


    public function runTenantSpecificSeeders()
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(ProvinceMunicipalitySeeder::class);
    }

    public function runLandlordSpecificSeeders()
    {
        // run landlord specific seeders
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(ProvinceMunicipalitySeeder::class);
        $this->call(AdminSeeder::class);
    }
}
