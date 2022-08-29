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
        ]);
        Label::create([
            'name' => 'My Project',
        ]);

        Note::factory(8)->create();
        NoteLabel::factory(1)->create();

        Tag::create([
            'name' => 'Programming',
        ]);
        Tag::create([
            'name' => 'Personal',
        ]);

        NoteTag::factory(5)->create();
    }
}
