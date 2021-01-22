@extends('adminlte::page')

@section('title', 'Manage Bookings')

@section('content_header')
    <h1 class="pull-left" style="margin-right: 20px">Manage Bookings</h1>
    
  <form style="width:300px;float:right;" action="{{ url('admin/booking/search') }}" method="GET">
        <div class="input-group">
            <input type="text" required value="{{ request('q') }}" class="form-control" name="q"
                   placeholder="Search Booking"> <span class="input-group-btn">
            <button type="submit" class="btn btn-default">
                <i class="fa fa-search" aria-hidden="true"></i>
            </button>
        </span>
        </div>
    </form>
    <br><br>
  

    <div class="clearfix"></div>
@endsection

@section('content')
    @include('admin.partials.status')
    <div class="card">
            <table id="example1" class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Event</th>
                    <th>Quantity</th>
                    <th>User</th>
                    <th>Date Created</th>
                    <th>Action</th>
                </tr>
            </thead>
                <tbody>
                @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id?? ''}}</td>
                        <td>
                            <a href="{{ url('/admin/event/readOnly') . '/' . $booking->event_id }}">
                                {{ $booking->event->name ?? ''}}
                            </a>
                        </td>
                        <td>{{ $booking->quantity ?? ''}}</td>
                        <td>
                            <a href="{{ url('/admin/user/readOnly') . '/' . $booking->user_id }}">
                                {{ $booking->user->first_name . ' ' . $booking->user->surname ?? ''}}
                            </a>
                        </td>
                        <td>{{ $booking->created_at ?? ''}}</td>
                        <td>
                            <a href="{{ url('/admin/booking/delete') . '/' . $booking->id }}" role="button"
                               name="deleteBooking" id="deleteBooking" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>Event</th>
                    <th>Quantity</th>
                    <th>User</th>
                    <th>Date Created</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    <div>{{ $bookings->appends(\Request::except('page'))->render()}}</div>
@endsection


