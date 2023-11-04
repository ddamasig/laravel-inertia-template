<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'first_name' => 'Dean Simon',
            'middle_name' => null,
            'last_name' => 'Damasig',
            'birthdate' => Carbon::now()->subYears(24),
            'mobile_number' => '09451339125',
            'email' => 'ddamasig@gmail.com',
        ]);
        $user = $user->refresh();

        $user->assignRole('Admin');

        $users = User::factory(20)->create();
        foreach ($users as $u) {
            $u->assignRole('Tester');
        }
    }
}
