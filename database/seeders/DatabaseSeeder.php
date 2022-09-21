<?php

namespace Database\Seeders;

use App\Models\Label;
use App\Models\Note;
use App\Models\NoteLabel;
use App\Models\NoteTag;
use App\Models\Tag;
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
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        Label::create([
            'name' => 'Thoughts',
            'user_id' => 2
        ]);
        Label::create([
            'name' => 'My Project',
            'user_id' => 2
        ]);
        Label::factory(10)->create();

        Note::factory(200)->create();

        for ($i = 1; $i <= 100; $i++) {
            NoteLabel::create([
                'note_id' => $i,
                'label_id' => mt_rand(1, 2)
            ]);
        }

        Tag::create([
            'name' => 'Programming',
            'user_id' => 2
        ]);
        Tag::create([
            'name' => 'Personal',
            'user_id' => 2
        ]);
        Tag::factory(20)->create();

        NoteTag::factory(5)->create();
    }
}
