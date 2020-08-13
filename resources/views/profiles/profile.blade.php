@extends('layouts.app')

@section('content')


<div class="container emp-profile">
    @if($user->id ==auth()->user()->id)
    <div class="clearfix">
        <a href="{{ route('profile.edit',$user->id) }}"
        class="btn float-right bg-warning"
        style="margin-bottom: 10px ">
          Edit Profile
        </a>
      </div>
      @endif
    <form method="post">
        <div class="row justify-content-center">
            <div class="col-md-8  ">
                <div class="profile-img">
                    <div class="file btn btn-lg btn-primary">
                        <img src="{{  $user->hasPicture() ? asset('storage/'.$user->getPicture()) : $user->getGravatar()}}" alt=""/>

                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center" >
            <div class= "col-md-12" style="margin:5%" ></div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="tab-content profile-tab" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Name</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{  $user->name}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Email</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{ $user->email }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Phone</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{  $user->phoneNumber}}</p>
                                    </div>
                                </div>
                                @if($profile->job != null)
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Profession</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{ $profile->job }}</p>
                                    </div>
                                </div>
                                @endif
                                @if($user->car != null)
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Driver license</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{ $car->userLicense }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Car license</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{ $car->license }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Car Model</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{ $car->carModel }}</p>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Car color</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{ $car->color }}</p>
                                    </div>
                                </div>


                                @endif

                    </div>

                </div>
        </div>

@endsection
