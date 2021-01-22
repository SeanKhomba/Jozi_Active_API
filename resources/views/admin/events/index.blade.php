@extends('adminlte::page')

@section('title', 'Manage Events')

@section('content_header')
    <h1 class="pull-left" style="margin-right: 20px">Manage Events</h1>
    
    <a href="{{ url('admin/event/create') }}" role="button" style="margin-top:20px"  class="pull-left btn btn-primary"><i
            class="fa fa-fw fa-plus"></i>
        Add Event
    </a>
  <form style="width:300px;float:right;" action="{{ url('admin/event/search') }}" method="GET">
        <div class="input-group">
            <input type="text" required value="{{ request('q') }}" class="form-control" name="q"
                   placeholder="Search Event"> <span class="input-group-btn">
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
    <style>

        .parent {
            position: relative;
            top: 0;
            left: 0;
        }
        .gif {
            position: absolute;
            top: 30px;
            left: 40px;
            width: 20px;
        }

    </style>
    <div class="card">
            <table id="example1" class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Quantity Available</th>
                    <th>Price</th>
                    <th>Avg Rating</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Active</th>
                    <th>Action</th>
                </tr>
            </thead>
                <tbody>
                @foreach($events as $event)
                    <tr>
                        <td>{{ $event->name ?? ''}}</td>
                        <td>{{ $event->category->name ?? ''}}</td>
                        <td>{{ $event->quantity_available ?? ''}}</td>
                        <td>R {{ $event->price ?? ''}}</td>
                        <td>{{ $event->avg_rating ?? 'N/A'}}</td>
                        <td>{{ \Carbon\Carbon::parse($event->date)->isoFormat('MMM Do YYYY') ?? ''}}</td>
                        <td>{{ $event->start_time ?? ''}}</td>
                        <td>{{ $event->end_time ?? ''}}</td>
                        @if($event->active == 1)
                        <td>{{ 'Yes' }}</td>
                        @else
                        <td>{{ 'No' }}</td>
                        @endif
                        <td>
                            <a href="{{ url('/admin/event/readOnly') . '/' . $event->id }}" role="button"
                                class="btn btn-warning btn-sm">View</a>
                            <a href="{{ url('/admin/event/edit') . '/' . $event->id }}" role="button"
                               class="btn btn-primary btn-sm">Edit</a>
                            <a href="{{ url('/admin/event/delete') . '/' . $event->id }}" role="button"
                               name="deleteEvent" id="deleteEvent" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Quantity Available</th>
                    <th>Price</th>
                    <th>Avg Rating</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Active</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    <div>{{ $events->appends(\Request::except('page'))->render()}}</div>
@endsection


