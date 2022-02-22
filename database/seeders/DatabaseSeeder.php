<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Administrator',
            'surnames' => 'Voicemod Reviewer',
            'country' => 'Spain',
            'phone' => '000000',
            'postal_code' => '123123-123123',
            'email' => 'admin@voicemod.com',
            'password' => bcrypt('password'),
        ]);
    }
}
