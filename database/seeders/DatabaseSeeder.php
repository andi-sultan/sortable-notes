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
            'name' => 'Admin Aaa',
            'email' => 'a@aaa.com',
            'password' => bcrypt('12345'),
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
        NoteLabel::factory(100)->create();

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
