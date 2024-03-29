<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    public $timestamps = false;

    #3
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    #2
    # To get the info of a like
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
