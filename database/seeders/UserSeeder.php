<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            [
                'name' => 'Govinda Kharisma Dewa',
                'email' => 'govindakharisma10@gmail.com',
                'email_verified_at' => null,
                'password' => bcrypt('govinda123'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Riska Febriyanti',
                'email' => 'riskafebriyanti@gmail.com',
                'email_verified_at' => null,
                'password' => bcrypt('riska123'),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ])->each(fn ($user) => DB::table('users')->insert($user));
    }
}
