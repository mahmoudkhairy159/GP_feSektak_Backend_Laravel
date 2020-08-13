<?php

namespace App\Http\Controllers;
use App\Notifications\RequestSent;
use App\Request;
use App\Ride;
use Illuminate\Http\Request as WebRequest;
use Illuminate\Support\Facades\Auth;


class RequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requests = Request::all()->where('user_id', Auth::user()->id);
        return view('requestts.index')->with('requestts', $requests);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('requestts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WebRequest $request)
    {
        Request::create([
            'meetPointLatitude' =>floatval( $request->meetPointLatitude),
            'meetPointLongitude' => floatval($request->meetPointLongitude),
            'destinationLatitude' => floatval($request->destinationLatitude),
            'destinationLongitude' => floatval($request->destinationLongitude),
            'neededSeats' => $request->neededSeats,
            'time' => $request->time,
            'user_id' => $request->user_id

        ]);
        session()->flash('flashMessage', 'Request is created successfully',['timeout' => 100]);
        return redirect(route('requests.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $requestt = Request::find($id);
        return view('requestts.create', ['requestt' => $requestt]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(WebRequest $request, $id)
    {
        $requestt = Request::find($id);
        $requestt->update([
            'meetPointLatitude' =>floatval( $request->meetPointLatitude),
            'meetPointLongitude' => floatval($request->meetPointLongitude),
            'destinationLatitude' => floatval($request->destinationLatitude),
            'destinationLongitude' => floatval($request->destinationLongitude),
            'neededSeats' => $request->neededSeats,
            'time' => $request->time,
            'user_id' => $request->user_id
        ]);
        session()->flash('flashMessage', 'request is updated successfully',['timeout' => 100]);
        return redirect(route('requests.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $requestt = Request::find($id);
        $requestt->delete();
        session()->flash('flashMessage', 'Request deleted successfully',['timeout' => 100]);
        return redirect(route('requests.index'));
    }


    public static function x(
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

    public function viewAvailableRides($id)
    {
        $requestt = Request::find($id);
        if ($requestt->response == false) {
            $rides = Ride::all()
            ->where('user_id', '<>', $requestt->user_id)
            ->where('time', '>=', $requestt->time)
            ->where('availableSeats', '>=', $requestt->neededSeats)
            ->where('available', true);
            $filtered = $rides->filter(function ($value, $key) use ($requestt) {
return (self::x(
                    $requestt->destinationLatitude,
                    $requestt->destinationLongitude,
                    $value->destinationLatitude,
                    $value->destinationLongitude
                )<5);
            });
            $rides== $filtered->values();
            return view('requestts.viewAvailableRides')->with('rides', $rides)->with('requestt', $requestt);
        }else{
            session()->flash('flashMessage', 'You already reserved a ride',['timeout' => 100]);
            $ride=$requestt->ride;
            //dd($ride);
           return view('requestts.myRide')->with('ride', $ride)->with('requestt', $requestt);
        }
    }
    public function sendRequest($request_id, $ride_id)
    {
        $requestt = Request::find($request_id);
        $requestt->ride_id = $ride_id;
        $requestt->save();
        $requestt->ride->user->notify(new RequestSent($requestt));   //driver

        session()->flash('flashMessage', 'Request is sent',['timeout' => 100]);
        $requestts=Request::all()->where('id','<>',$requestt->id);
        return view('requestts.index')->With('requestts',$requestts);
    }
    public function cancelRide($request_id, $ride_id)
    {
        $requestt = Request::find($request_id);
        $requestt->ride->availableSeats=$requestt->ride->availableSeats+ $requestt->neededSeats;
        $requestt->response=false;
        $requestt->ride_id = NULL;
        $requestt->save();
        session()->flash('flashMessage', 'Request to Ride is canceled ',['timeout' => 100]);
        return redirect(route('requests.index'));
    }
}
