<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Notifications\RequestSent;
use App\Notifications\RequestCanceled;
use App\Notifications\RequestAccepted;
use App\Request;
use App\Ride;
use Illuminate\Http\Request as WebRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;

class RequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->content = array();
    }
    public function index()
    {
        $user = User::findOrFail(request('userId'));
        $this->content['requests'] =  $user->requests;
        return response()->json($this->content);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $rules = [
            'meetPointLatitude' => ['required'],
            'meetPointLongitude' => ['required'],
            'endPointLatitude' => ['required'],
            'endPointLongitude' => ['required'],
            'numberOfNeededSeats' => ['required','regex:/^[1234]$/'],
            'time' => ['required'],
            'userId' => ['required']
        ];
        $validator = Validator::make( request()->all(), $rules);
        if ($validator->passes()) {
            Request::create([
            'meetPointLatitude' => request('meetPointLatitude'),
            'meetPointLongitude' => request('meetPointLongitude'),
            'destinationLatitude' => request('endPointLatitude'),
            'destinationLongitude' => request('endPointLongitude'),
            'neededSeats' => request('numberOfNeededSeats'),
            'time' => request('time'),
            'user_id' => request('userId'),
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
     * @param  \App\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $rules = [
            'meetPointLatitude' => ['required'],
            'meetPointLongitude' => ['required'],
            'endPointLatitude' => ['required'],
            'endPointLongitude' => ['required'],
            'numberOfNeededSeats' => ['required','regex:/^[1234]$/'],
            'time' => ['required'],
        ];
        $validator = Validator::make( request()->all(), $rules);
        if ($validator->passes()) {
            $request=Request::find(request('requestId'));
            $request->update([
            'meetPointLatitude' => request('meetPointLatitude'),
            'meetPointLongitude' =>request('meetPointLongitude'),
            'destinationLatitude' =>request('endPointLatitude'),
            'destinationLongitude' => request('endPointLongitude'),
            'neededSeats' => request('numberOfNeededSeats'),
            'time' => request('time'),
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $request = Request::find(request('requestId'));
        if ($request!=null) {
            $request->delete();
            $this->content['status'] = 'done';
            return response()->json($this->content);
        } else {
            $this->content['status'] = 'already deleted';
            return response()->json($this->content);
        }
    }

 public function reject()
    {
        $request = Request::find(request('requestId'));
        $request->ride_id = null;
        $request->save();
	    $this->content['status'] = 'done';
        return response()->json($this->content);
    }

    public function sendRequest()
    {
        $request= Request::findOrFail(request('requestId'));
        $request->ride_id = request('rideId');
        $request->save();
        $request->ride->user->notify(new RequestSent($request));   //driver
        $this->content['status'] = 'done';
        return response()->json($this->content);
    }


    public function acceptRequest()
    {
        $request = Request::find(request('requestId'));
        $ride = Ride::find(request('rideId'));
        if ($ride->availableSeats >= $request->neededSeats && $request->response == false) {
            $request->update([
                'response' => true,
                'ride_id' => $ride->id,
            ]);
            $ride->update([
                'availableSeats' => $ride->availableSeats - $request->neededSeats,
            ]);
            $request->user->notify(new RequestAccepted($ride));   //driver
            $this->content['status'] = 'done';
            return response()->json($this->content);
        } else {
            $this->content['status'] = 'unAvailable';
            return response()->json($this->content);
        }
    }

    public function cancelRequest()
    {
        $user=User::find(request('userId'));
        $request = Request::find(request('requestId'));
        if($user->id !=  $request->user->id){//driver canel request
            $request->user->notify(new RequestCanceled($request,$user)); //notify passenger
        }else{//passenger cancel request
            $request->ride->user->notify(new RequestCanceled($request,$user)); //notify driver
        }
        $request->ride->availableSeats=$request->ride->availableSeats + $request->neededSeats;
        $request->ride->save();
        $request->response=false;
        $request->ride_id = NULL;
        $request->save();
        $this->content['status'] = 'done';
        return response()->json($this->content);
    }


}
