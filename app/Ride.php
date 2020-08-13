<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Request;
class Ride extends Model
{
    protected $fillable=['startPointLatitude','startPointLongitude','destinationLatitude','destinationLongitude','availableSeats','time','available','user_id'];
    public function user()
    {
        return $this->belongsTo(User::class);

    }
    public function requests()
    {
        return $this->hasMany(Request::class);
    }

}
