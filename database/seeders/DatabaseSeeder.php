<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::table('roles')->insert([
            'name' => 'Super Admin',
        ]);
        DB::table('roles')->insert([
            'name' => 'Admin',
        ]);
        DB::table('roles')->insert([
            'name' => 'Anggota',
        ]);

        $superAdmin = User::factory()->create();
        $superAdmin->role()->attach(1);
        $anggota = [
            [
                'name' => fake()->name(),
                'nim' => '4.33.21.2.17',
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => Hash::make('123456789'),
            ],
            [
                'name' => fake()->name(),
                'nim' => '4.33.21.2.18',
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => Hash::make('123456789'),
            ],
        ];

        foreach ($anggota as $a) {
            $user = User::create($a);
            $user->role()->attach(3);
        }

        $admin = [
            'name' => fake()->name(),
            'nim' => '4.33.21.2.19',
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('123456789'),
        ];

        Organization::create([
            'name_organization' => 'PCC',
        ]);

        $adminUser = User::create($admin);
        $adminUser->role()->attach(2);
        $adminUser->organization()->attach(1);
    }
}
