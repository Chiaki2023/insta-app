<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    public $timestamps = false;

    # To get the info of a follower
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id')->withTrashed();
    }

    # One to many (inverse)
    # To get the info of the user being followed
    public function followingUser()
    {
        return $this->belongsTo(User::class, 'following_id')->withTrashed();
    }
}
