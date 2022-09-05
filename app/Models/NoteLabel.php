<?php

namespace App\Models;

use App\Models\Note;
use App\Models\Label;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NoteLabel extends Model
{
    use HasFactory;
    protected $fillable = ['note_id', 'label_id'];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function label()
    {
        return $this->belongsTo(Label::class);
    }
}
