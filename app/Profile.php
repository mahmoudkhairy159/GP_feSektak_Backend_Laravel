<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable=['job','picture','rate','services','user_id','totalReview'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
