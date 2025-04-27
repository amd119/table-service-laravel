<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin',
                'password' => Hash::make('admin'),
                'role' => 'administrator',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'username' => 'waiter',
                'password' => Hash::make('waiter'),
                'role' => 'waiter',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'username' => 'kasir',
                'password' => Hash::make('kasir'),
                'role' => 'kasir',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'username' => 'owner',
                'password' => Hash::make('owner'),
                'role' => 'owner',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        
        User::insert($users);
    }
}