<?php

namespace App\Http\Controllers;
use App\Notifications\RequestAccepted;
use App\Ride;
use App\Request;
use Illuminate\Http\Request as WebRequest;
use Illuminate\Support\Facades\Auth;


class RidesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $rides = Ride::all()->where('user_id', Auth::user()->id);
        return view('rides.index')->with('rides', $rides);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('rides.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WebRequest $request)
    {
        Ride::create([
            'startPointLatitude' => floatval($request->startPointLatitude),
            'startPointLongitude' =>floatval( $request->startPointLongitude),
            'destinationLatitude' => floatval($request->destinationLatitude),
            'destinationLongitude' => floatval($request->destinationLongitude),
            'availableSeats' => $request->availableSeats,
            'time' => $request->time,
            'user_id' => $request->user_id
        ]);
        session()->flash('flashMessage', 'Ride is created successfully');
        return redirect(route('rides.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ride  $ride
     * @return \Illuminate\Http\Response
     */
    public function show(Ride $ride)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ride  $ride
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ride = Ride::find($id);
        return view('rides.create', ['ride' => $ride]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ride  $ride
     * @return \Illuminate\Http\Response
     */
    public function update(WebRequest $request, $id)
    {
        $ride = Ride::find($id);
        $ride->update([
            'startPointLatitude' => floatval($request->startPointLatitude),
            'startPointLongitude' =>floatval( $request->startPointLongitude),
            'destinationLatitude' => floatval($request->destinationLatitude),
            'destinationLongitude' => floatval($request->destinationLongitude),
            'availableSeats' => $request->availableSeats,
            'time' => $request->time,
            'user_id' => $request->user_id
        ]);
        session()->flash('flashMessage', 'Ride is updated successfully', ['timeout' => 100]);
        return redirect(route('rides.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ride  $ride
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ride = Ride::find($id);
        $ride->delete();
        session()->flash('flashMessage', 'Ride is deleted successfully', ['timeout' => 100]);
        return redirect(route('rides.index'));
    }

    public function viewSentRequests($id)
    {
        $ride = Ride::find($id);
        $requests = Ride::find($id)->requests->where('neeededSeats', '<=', $ride->availableSeats)->where('response', false);
        return view('rides.viewSentRequests')->with('requests', $requests)->with('ride', $ride);
    }




    public function acceptRequest($request_id, $ride_id)
    {
        $requestt = Request::find($request_id);
        $ride = Ride::find($ride_id);
        if ($ride->availableSeats >= $requestt->neededSeats && $requestt->response == false) {
            $requestt->update([
                'response' => true,
                'ride_id' => $ride->id,
            ]);
            $ride->update([
                'availableSeats' => $ride->availableSeats - $requestt->neededSeats,
            ]);
            $requestt->user->notify(new RequestAccepted($ride));   //driver
            session()->flash('flashMessage', 'Request is accepted successfully', ['timeout' => 100]);
            $requests = Request::where('id', '<>', $request_id)->get();
            return view('rides.viewSentRequests')->with('requests', $requests)->with('ride', $ride);
        } else {
            $requests = Request::where('id', '<>', $request_id)->where('response',false);
            if ($requests->count() > 0) {
                session()->flash('flashMessage', 'You do not have enough seats for this request', ['timeout' => 100]);
            }
            return view('rides.viewSentRequests')->with('requests', $requests)->with('ride', $ride);
        }

    }
}
