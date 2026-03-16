<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $user = User::query()->updateOrCreate(
                ['email' => 'admin@g.msuiit.edu.ph'],
                [
                    'first_name' => 'System',
                    'middle_name' => null,
                    'last_name' => 'Admin',
                    'birthdate' => null,
                    'age' => null,
                    'sex' => null,
                    'birthplace' => null,
                    'religion' => null,
                    'civil_status' => null,
                    'number_of_children' => 0,
                    'citizenship' => null,
                    'address' => null,
                    'phone_number' => null,
                    'password' => Hash::make('1234567890'),
                    'role' => 'admin',
                ]
            );

            Admin::query()->updateOrCreate(
                ['user_id' => $user->id],
                ['credentials' => 'Administrator']
            );
        });
    }
}
