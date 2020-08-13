@extends('layouts.app')

@section('stylesheets')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.0/trix.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
    <div class="card card-default">
        <div class="card-header">
            {{ isset($ride) ? "Update ride" : "make ride" }}
        </div>
        <div class="card-body">
        <form action="{{ isset($ride) ? route('rides.update', $ride->id) : route('rides.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if (isset($ride))
                  @method('PUT')
                @endif
                <div class="form-group">
                    <label for="startPoint">StartPointLatitude:</label>
                <input type="text" class="form-control" name="startPointLatitude" placeholder="Enter the StartPointLatitude" value="{{ isset($ride) ? $ride->startPointLatitude: ''}}">
                </div>
                <div class="form-group">
                    <label for="startPoint">StartPointLongitude:</label>
                <input type="text" class="form-control" name="startPointLongitude" placeholder="Enter the StartPointLongitude" value="{{ isset($ride) ? $ride->startPointLongitude: ''}}">
                </div>
                <div class="form-group">
                    <label for="Destination">destinationLatitude:</label>
                    <input type="text" class="form-control" name="destinationLatitude" placeholder="Enter your destinationLatitude" value="{{ isset($ride) ? $ride->destinationLatitude: ''}}">
                </div>
                <div class="form-group">
                    <label for="Destination">destinationLongitude:</label>
                    <input type="text" class="form-control" name="destinationLongitude" placeholder="Enter your destinationLongitude" value="{{ isset($ride) ? $ride->destinationLongitude: ''}}">
                </div>
                <div class="form-group">
                    <label for="Time">Time:</label>
                    <input type="time" class="form-control" name="time" value="{{ isset($ride) ? $ride->time: ''}}">
                </div>
                <div class="form-group">
                    <label for="neededSeats">Available Seats:</label>
                    <input type="number" class="form-control" name="availableSeats" min="1" max="4" placeholder="Enter number of available seats " value="{{ isset($ride) ? $ride->availableSeats: ''}}">
                </div>
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                <div class="form-group">
                    <button type="submit" class="btn btn-success">
                        {{ isset($ride) ? 'Update' : 'Add' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.0/trix.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

    <script>
      $(document).ready(function() {
        $('.tags').select2();
      });
    </script>
@endsection
