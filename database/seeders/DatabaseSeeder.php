<?php

namespace Database\Seeders;

use App\Models\Label;
use App\Models\Note;
use App\Models\PermanentNote;
use App\Models\Project;
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
        User::factory(1)->create();

        User::create([
            'name' => 'Admin Aaa',
            'email' => 'a@aaa.com',
            'password' => bcrypt('12345'),
        ]);

        Label::create([
            'name' => 'Programming',
        ]);
        Label::create([
            'name' => 'Web Design',
        ]);

        Note::factory(8)->create();

        Project::create([
            'name' => 'My App',
        ]);
        Project::create([
            'name' => 'My Project',
        ]);

        PermanentNote::factory(8)->create();
    }
}
