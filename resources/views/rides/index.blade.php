@extends('layouts.app')

@section('content')
<div class="clearfix">
    <a href="{{ route('rides.create') }}" class="btn float-right btn-success" style="margin-bottom: 10px">
        Make Ride
    </a>
</div>

<div class="card card-default">
    <div class="card-header">My Rides</div>
    @if ($rides->count() > 0)
    <table class="card-body">
        <table class="table card-body table-bordered  table-hover table-lg">
            <thead class="thead-dark">
                <tr>
                    <th>startPoint Latitude</th>
                    <th>startPoint Longitude</th>
                    <th>destination Latitude</th>
                    <th>destination Longitude</th>
                    <th>Time</th>
                    <th>AvailableSeets</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rides as $ride)
                <tr>
                    <td>
                        {{$ride->startPointLatitude  }}
                      </td>
                      <td>
                          {{$ride->startPointLongitude }}
                      </td>
                      <td>
                        {{ $ride->destinationLatitude }}
                      </td>
                      <td>
                          {{ $ride->destinationLongitude }}
                      </td>
                    <td>
                        {{ $ride->time }}
                    </td>
                    <td>
                        <span class="ml-2 badge badge-primary">{{ $ride->availableSeats }}</span>
                    </td>

                    <td>
                        <form class="float-right ml-2" action="{{route('rides.destroy', $ride->id)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">
                                Delete
                            </button>
                        </form>
                        <a href="{{route('rides.edit', $ride->id)}}" class="btn btn-primary float-right btn-sm">Edit</a>
                        <a href="{{route('rides.viewSentRequests', $ride->id)}}"
                            class="btn btn-primary float-right btn-sm" style="margin-right:3%; ">View Requests </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="card-body">
            <h1 class="text-center">
                No Rides Yet.
            </h1>
        </div>
        @endif
</div>
</div>


@endsection
