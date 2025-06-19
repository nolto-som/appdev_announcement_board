<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['title', 'content', 'status', 'user_id', 'image'];

    public function user()
{
    return $this->belongsTo(User::class);
}
}


