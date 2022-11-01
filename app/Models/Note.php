<?php

namespace App\Models;

use App\Models\NoteTag;
use App\Models\NoteLabel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function noteLabel()
    {
        return $this->hasOne(NoteLabel::class);
    }

    public function noteTag()
    {
        return $this->hasMany(NoteTag::class); // todo
    }
}
