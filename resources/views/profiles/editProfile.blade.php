@extends('layouts.app')

@section('content')
    <div class="card card-default">
        <div class="card-header">
            Profile
        </div>
        <div class="card-body">
        <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Name:</label>
                  <input type="text" name="name" class="form-control" value="{{$user->name}}">
                </div>
                <div class="form-group">
                  <label for="email">Email:</label>
                  <input type="text" name="email" class="form-control" value="{{$user->email}}">
                </div>
                <div class="form-group">
                    <label for="job">Job:</label>
                    <input type="text" name="job" class="form-control" value="{{$profile->job}}">
                  </div>
                <div class="form-group">
                  <label for="about">Mobile:</label>
                  <input id="phoneNumber" type="text" class="form-control @error('phoneNumber') is-invalid @enderror" name="phoneNumber" value="{{ $user->phoneNumber}}" >

                </div>
                @if($user->car!=null)
                <div class="form-group">
                  <label for="license">Car License:</label>
                  <input type="text" name="license" class="form-control" value="{{$car->License}}">
                </div>
                <div class="form-group">
                  <label for="model">Car Model:</label>
                  <input type="text" name="model" class="form-control" value="{{$car->carModel}}">
                </div>
                <div class="form-group">
                    <label for="color">Car Color:</label>
                    <input type="text" name="color" class="form-control" value="{{$car->color}}">
                  </div>
                @endif


                <div class="form-group">
                  <label for="picture">Picture:</label><br>
                  <img src="{{$user->hasPicture() ? asset('storage/'.$user->getPicture()) : $user->getGravatar()}}" width="80px" height="80px">
                  <input type="file" name="picture" class="form-control mt-2">
                </div>

                <div class="form-group">
                    <button class="btn btn-success">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
