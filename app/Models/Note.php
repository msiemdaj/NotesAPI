<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['title', 'body', 'user_id'])]
class Note extends Model
{
    use SoftDeletes, HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
