<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            SourceSeeder::class,
            ResponseSeeder::class,
        ]);

        App\User::create([
            'name' => 'nate',
            'email' => 'nate@eachevery.com',
            'password' => Hash::make('password'),
        ]);
    }
}
