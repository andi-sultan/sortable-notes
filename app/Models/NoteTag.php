<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Note;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NoteTag extends Model
{
    use HasFactory;
    protected $fillable = ['note_id', 'tag_id', 'position'];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function tag()
    {
        return $this->belongsToMany(Tag::class);
    }
}
