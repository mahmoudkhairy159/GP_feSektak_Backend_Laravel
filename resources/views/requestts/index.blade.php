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
    <div class="card-header">My Requests</div>
        @if ($requestts->count() > 0)
          <table class=" table card-body table-bordered  table-hover table-lg">
              <thead class="thead-dark">
                <tr>
                  <th>meetPoint Latitude </th>
                  <th>meetPoint Longitude </th>
                  <th>destination Latitude</th>
                  <th>destination Longitude</th>
                  <th>Time</th>
                  <th>NeededSeets</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($requestts as $requestt)
                  <tr>
                    <td>
                      {{$requestt->meetPointLatitude  }}
                    </td>
                    <td>
                        {{$requestt->meetPointLongitude  }}
                    </td>
                    <td>
                      {{ $requestt->destinationLatitude}}
                    </td>
                    <td>
                        {{ $requestt->destinationLongitude }}
                    </td>
                    <td>
                        {{ $requestt->time }}
                    </td>
                    <td>
                        <span class="ml-2 badge badge-primary">{{ $requestt->neededSeats }}</span>
                    </td>

                    <td>
                      <a href="{{route('requests.edit', $requestt->id)}}" class="btn btn-primary float-right btn-sm">Edit</a>
                      <a href="{{route('requests.viewAvailableRides', $requestt->id)}}" class="btn btn-primary float-right btn-md" style="margin-right:3%; "> Available rides</a>
                      <form class="float-right ml-2"
                      action="{{route('requests.destroy', $requestt->id)}}" method="POST">
                        @csrf
                        @method('DELETE')
                          <button class="btn btn-danger btn-sm">
                            Delete
                        </button>
                      </form>

                    </td>
                  </tr>
                @endforeach
          </table>
        @else
          <div class="card-body">
            <h1 class="text-center">
               No Requests Yet
            </h1>
          </div>
        @endif
    </div>
</div>


@endsection
