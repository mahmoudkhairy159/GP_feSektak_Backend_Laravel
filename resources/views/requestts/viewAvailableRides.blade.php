@extends('layouts.app')

@section('content')
  <div class="clearfix">
    <a href="{{ route('requests.create') }}"
    class="btn float-right btn-success"
    style="margin-bottom: 10px">
      Make Request
    </a>
  </div>

  <div class="card card-default table-responsive-lg">
    <div class="card-header">Available Rides</div>
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
                      <form class="float-right ml-2"
                      action="{{route('requsetts.sendRequest', ['request_id' => $requestt->id, 'ride_id' => $ride->id])}}" method="GET">
                        @csrf

                          <button class="btn btn-danger btn-sm">
                            send request
                        </button>
                      </form>

                        <a href="{{ route('users.showProfile',$ride->user->id) }}" class="btn btn-primary float-right btn-sm" style="margin-right:3%; ">View User profile  </a>
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
