@extends('layouts.app')

@section('content')
<div class="clearfix">
    <a href="{{ route('rides.create') }}" class="btn float-right btn-success" style="margin-bottom: 10px">
        Make Ride
    </a>
</div>

<div class="card card-default">
    <div class="card-header">Sent Requests</div>
    @if ($requests->count() > 0)
    <table class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>meetPoint Latitude </th>
                    <th>meetPoint Longitude </th>
                    <th>destination Latitude</th>
                    <th>destination Longitude</th>
                    <th>Time</th>
                    <th>AvailableSeets</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $request)
                <tr>
                    <td>
                        {{$request->meetPointLatitude  }}
                    </td>
                    <td>
                        {{$request->meetPointLongitude  }}
                    </td>
                    <td>
                        {{ $request->destinationLatitude}}
                    </td>
                    <td>
                        {{ $request->destinationLongitude }}
                    </td>

                    <td>
                        {{ $request->time }}
                    </td>
                    <td>
                        <span class="ml-2 badge badge-primary">{{ $request->neededSeats }}</span>
                    </td>

                    <td>
                        <form class="float-right ml-2"
                            action="{{ route('rides.acceptRequest' ,['request_id'=>$request->id, 'ride_id'=>$ride->id ])}}"
                            method="GET">
                            @csrf

                            <button class="btn btn-danger btn-sm">
                                Accept
                            </button>
                        </form>

                        <a href="{{ route('users.showProfile',$request->user->id) }}"
                            class="btn btn-primary float-right btn-sm" style="margin-right:3%; ">View User profile </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="card-body">
            <h1 class="text-center">
                No Requests .
            </h1>
        </div>
        @endif
</div>
</div>


@endsection
