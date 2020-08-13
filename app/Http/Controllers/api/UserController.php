<?php

namespace App\Http\Controllers\api;
use App\Events\LocationsSent;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Car;
use App\Profile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->content = array();
    }
    public function updateProfilePicture()
    {
	$user = User::findOrFail(request('userId'));
	$rules= ['picture' => ['required','image'],];
	$validator = Validator::make(request()->all(),$rules);
	if($validator->passes()){
		$imagePath = request('picture')->store('uploads','public');
		$user->profile->update([
                            'picture' => $imagePath,
                        ]);
	}
	$this->content['status'] = 'done';
        return response()->json($this->content);
    }
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            if($user->hasVerifiedEmail()){
                $this->content['user'] = $user;
                $profile = $user->profile;
                $profile->picture = url()->previous().'\/\/\/\/storage\/\/\/\/'.$profile->picture;
                $this->content['user']['profile'] = $profile;
                $this->content['user']['car'] = $user->car;
                return response()->json($this->content);
            }else{
                $this->content['error'] = "Please Verify Your Mail";
                return response()->json($this->content);
            }
        } else {
            $this->content['error'] = "Unauthorized";
            return response()->json($this->content);
        }
    }
    public function getById()
    {
        $user=User::find(request('userId'));
	$profile = $user->profile;
 	$profile->picture = url()->previous().'\/\/\/\/storage\/\/\/\/'.$profile->picture;
        $user['profile']=$profile;
	    $user['car']=$user->car;
        return $user;
    }
    public function register()
    {
        $data = request()->all();
        $rules = [
            'name' => ['required', 'string', 'max:255' ,'regex:/^[a-zA-Z]/'],
            'email' => ['required', 'string', 'unique:users','email', 'max:255', 'unique:users','regex:/^[a-zA-Z0-9]{0,}([.]?[a-zA-Z0-9]{1,})[@](gmail.com|hotmail.com|yahoo.com)$/'],
            'password' => ['required', 'string', 'min:8'],
            'phoneNumber' => ['required', 'string', 'min:8', 'unique:users','regex:/^(0110|0111|0112)[0-9]{7}$/'],
            'nationalId' => ['required', 'string', 'min:8', 'unique:users','regex:/^(2)[0-9]{13}$/']
        ];
        $carRules = [
            'license' => ['required','min:8','unique:cars','regex:/^[a-zA-Z0-9]{8}$/'],
            'model' => ['required','string',],
            'color' => ['required','string',],
            'userLicense' => ['required','min:8','unique:cars','regex:/^[0-9]{8}$/'],
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->passes()) {
            $user = new User;
            $user->name = request('name');
            $user->email = request('email');
            $user->phoneNumber = request('phoneNumber');
            $user->nationalId = request('nationalId');
            $user->password = Hash::make(request('password'));
//            dd(request('car')['license']);
            if (request('car') != null) {
                $validator = Validator::make(request('car'), $carRules);
                if ($validator->passes()) {
                    $car = new Car;
                    $car->license = request('car')['license'];
                    $car->carModel = request('car')['model'];
                    $car->color = request('car')['color'];
                    $car->userLicense = request('car')['userLicense'];
                    $user->save();
                    $user->sendEmailVerificationNotification();
                    $car->user_id = $user->id;
                    $car->save();
                    $profile = Profile::create([
                        'user_id' => $user->id,
                        'picture' => $user->getGravatar(),
                    ]);
                    $this->content['status'] = 'done';
                    return response()->json($this->content);
                } else {
                    $this->content['status'] = 'undone';
                    $this->content['details'] = $validator->errors()->all();
                    return response()->json($this->content);
                }
            }
            $user->save();
            $user->sendEmailVerificationNotification();
            $profile = Profile::create([
                'user_id' => $user->id,
            ]);
            $this->content['status'] = 'done';
        } else {
            $this->content['status'] = 'undone';
            $this->content['details'] = $validator->errors()->all();
        }
        return response()->json($this->content);
    }
    public function details()
    {
        return response()->json(['user' => Auth::user()]);
    }







    public function destroy()
    {
        $user = User::find(request('userId'));
        if($user!=null){
            $user->delete();
            $this->content['status'] = 'done';
            return response()->json($this->content);
        }else{
           $this->content['status'] = 'already deleted';
           return response()->json($this->content);
        }

    }
    public function calcUserTotalReview()
    {
        $user = User::find(request('userId'));
        $profile=$user->profile;

        $rate=$profile->rate+ request('rate');
        $numOfServices=$profile->services + 1;
        $totalReview=round($rate/$numOfServices,2);
        $profile->update([
            'rate'=>$rate,
            'services'=>$numOfServices,
            'totalReview'=>$totalReview,
        ]);

            $this->content['status']='done' ;
            return response()->json($this->content);

    }






    //new function edit user:
    public function edit()
    {
        $data = request()->all();
        $user = User::findOrFail(request('user_id'));
        $rules = [
            'name' => ['required', 'string', 'max:255' ,'regex:/^[a-zA-Z]/'],
            'phoneNumber' => ['required', 'string', 'min:8','regex:/^(0110|0111|0112)[0-9]{7}$/'],
            'password' => ['required', 'string', 'min:8'],
            Rule::unique('phoneNumber')->ignore($user->mobileNum),


        ];
        $carRules = [
                'license' => ['required','min:8','regex:/^[a-zA-Z0-9]{8}$/'],
                Rule::unique('license')->ignore($user->license),

                'model' => ['required','string',],
                'color' => ['required','string',],
                'userLicense' => ['required','min:8','regex:/^[0-9]{8}$/'],
                Rule::unique('userLicense')->ignore($user->userLicense),

        ];
        $validator = Validator::make($data, $rules);
        if ($validator->passes()) {

            if (request('car') != null) {
                $validator = Validator::make(request('car'), $carRules);
                if ($validator->passes()) {
                    $car=$user->car;
                    if($car!=null){

                        $car->update([
                            'license' => request('car')['license'],
                            'carModel' => request('car')['model'],
                            'color' => request('car')['color'],
                            'userLicense' => request('car')['userLicense'],
                        ]);

                    }else{

                        $car = $user->car()->create([
                            'license' => request('car')['license'],
                            'carModel' => request('car')['model'],
                            'color' => request('car')['color'],
                            'userLicense' => request('car')['userLicense'],

                        ]);


                    }



                } else {
                    $this->content['status'] = 'undone';
                    $this->content['details'] = $validator->errors()->all();
                    return response()->json($this->content);
                }
            }
            $user->update([
                'name'=>request('name'),
                'mobileNum'=>request('phoneNumber'),
                'password'=>Hash::make(request('password')),
            ]);
            $this->content['status'] = 'done';
            return response()->json($this->content);
        } else {
            $this->content['status'] = 'undone';
            $this->content['details'] = $validator->errors()->all();
            return response()->json($this->content);
        }
    }



    public function send(){
        event(new LocationsSent(
            request('rideId'),
            request('userId'),
            request('locationLatitude'),
            request('locationLongitude')));
     }






}
