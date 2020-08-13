<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Ride;

class Request extends Model
{
    protected $fillable=['meetPointLatitude','meetPointLongitude','destinationLatitude','destinationLongitude','neededSeats','time','response','user_id','ride_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }


}
