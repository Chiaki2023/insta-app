<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    # To get the number of posts under a category
    public function categoryPosts()
    {
        return $this->hasMany(CategoryPost::class);
    }
}
