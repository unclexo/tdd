<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * Empty array means all are mass assignable in this case
     */
    protected $guarded = [];

    public function path()
    {
        return route('posts.show', $this->id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
