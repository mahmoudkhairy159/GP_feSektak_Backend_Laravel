@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Car Details') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('car.fillDetails',auth()->user()->id) }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Driver License') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control " name="userLicense" value="{{ old('userLicense') }}" required  autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Car License') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control " name="license" value="{{ old('license') }}" required  autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="model" class="col-md-4 col-form-label text-md-right">{{ __('Car Model') }}</label>

                            <div class="col-md-6">
                                <input id="model" type="text" class="form-control " name="model" value="{{ old('model') }}" required >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="color" class="col-md-4 col-form-label text-md-right">{{ __('Car Color') }}</label>
                            <div class="col-md-6">
                                <input id="color" type="text" class="form-control " name="color" value="{{ old('color') }}" required autofocus>

                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('ENTER') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
