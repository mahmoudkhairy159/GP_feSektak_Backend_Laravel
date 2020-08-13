<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Car extends Model
{
    protected $fillable=['userLicense','license','carModel','color','user_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
