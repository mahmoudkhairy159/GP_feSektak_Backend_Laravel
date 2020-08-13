<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Notifications\RideCanceled;
use App\Ride;
use App\User;
use App\Request;
use Illuminate\Http\Request as WebRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RidesController extends Controller
{
    public function __construct()
    {
        $this->content = array();
    }
    public function index()
    {
        $user = User::findOrFail(request('userId'));
        $rides = $user->rides;
        foreach ($rides as $ride){
            if($ride->requests){
                $ride['requests'] = $ride->requests;
            }
        }
        $this->content['rides'] = $rides;
        return response()->json($this->content);
     }


     public function destroy()
     {
         $ride = Ride::findOrFail(request('rideId'));
         if ($ride!=null) {
             $ride->delete();
             $this->content['status'] = 'done';
             return response()->json($this->content);
         }else{
            $this->content['status'] = 'already deleted';
            return response()->json($this->content);
         }
     }

public static function calcDistance(
        $latitudeFrom,
        $longitudeFrom,
        $latitudeTo,
        $longitudeTo
    ) {
        $long1 = deg2rad($longitudeFrom);
        $long2 = deg2rad($longitudeTo);
        $lat1 = deg2rad($latitudeFrom);
        $lat2 = deg2rad($latitudeTo);

        $dlong = $long2 - $long1;
        $dlati = $lat2 - $lat1;

        $val = pow(sin($dlati/2), 2)+cos($lat1)*cos($lat2)*pow(sin($dlong/2), 2);

        $res = 2 * asin(sqrt($val));

        $radius = 3958.756;

        return ($res*$radius);
    }




    public function viewAvailableRides()
    {
        $request = Request::findOrFail(request('requestId'));
        if ($request->response == false) {
            $rides = Ride::all()
            ->where('user_id', '<>', $request->user_id)
            ->where('time', '>=', $request->time)
            ->where('availableSeats', '>=', $request->neededSeats)
            ->where('available', true);
            $filtered = $rides->filter(function ($ride, $key) use ($request) {
return (self::calcDistance(
                    $request->destinationLatitude,
                    $request->destinationLongitude,
                    $ride->destinationLatitude,
                    $ride->destinationLongitude
                )<=5);
            });

            $price=self::calcDistance(
                $request->meetPointLatitude,
                $request->meetPointLongitude,
                $request->destinationLatitude,
                $request->destinationLongitude
            )*.5;
          
            $this->content['rides'] = $filtered->values();
            $this->content['price'] = round($price,2);

            return response()->json($this->content);
        } else {
            $this->content['rides'] = $request->ride;
            return response()->json($this->content);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WebRequest $request)
    {
        $data = request()->all();
        $rules = [
            'startPointLatitude' => ['required'],
            'startPointLongitude' => ['required'],
            'endPointLatitude' => ['required'],
            'endPointLongitude' => ['required'],
            'availableSeats' => ['required','regex:/^[01234]$/'],
            'time' => ['required'],
            'userId' => ['required']

        ];
        $validator = Validator::make($data, $rules);
        if ($validator->passes()) {
            Ride::create([
                'startPointLatitude' =>request('startPointLatitude'),
                'startPointLongitude' =>request('startPointLongitude'),
                'destinationLatitude' =>request('endPointLatitude'),
                'destinationLongitude' =>request('endPointLongitude'),
                'availableSeats' =>request('availableSeats'),
                'time' => request('time'),
                'user_id' => request('userId')
            ]);
            $this->content['status'] = 'done';
            return response()->json($this->content);
        } else {
            $this->content['status'] = 'undone';
            $this->content['details'] = $validator->errors()->all();
            return response()->json($this->content);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ride  $ride
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $data = request()->all();
        $rules = [
            'startPointLatitude' => ['required'],
            'startPointLongitude' => ['required'],
            'endPointLatitude' => ['required'],
            'endPointLongitude' => ['required'],
            'availableSeats' => ['required','regex:/^[01234]$/'],
            'time' => ['required'],
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->passes()) {
            $ride=Ride::find(request('rideId'));
            $ride->update([
                'startPointLatitude' =>request('startPointLatitude'),
                'startPointLongitude' =>request('startPointLongitude'),
                'destinationLatitude' =>request('endPointLatitude'),
                'destinationLongitude' =>request('endPointLongitude'),
                'availableSeats' =>request('availableSeats'),
                'time' => request('time'),
                'available' => request('available'),
            ]);
            $this->content['status'] = 'done';
            return response()->json($this->content);
    } else {
            $this->content['status'] = 'undone';
            $this->content['details'] = $validator->errors()->all();
            return response()->json($this->content);
    }
    }

    public function cancelRide()
    {
        $ride = Ride::find(request('rideId'));
        foreach($ride->requests as $request){
            $request->user->notify(new RideCanceled($request)); //notify passenger
            $request->response=false;
            $request->ride_id = NULL;
            $request->save();

        }
        $ride->delete();
        $this->content['status'] = 'done';
        return response()->json($this->content);
    }








}
