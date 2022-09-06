<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'user_id'];

    public function noteLabels()
    {
        return $this->hasMany(NoteLabel::class);
    }
}
