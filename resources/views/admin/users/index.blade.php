@extends('adminlte::page')

@section('title', 'Manage Users')

@section('content_header')
    <h1 class="pull-left" style="margin-right: 20px">Manage Users</h1>
    
    <a href="{{ url('admin/user/create') }}" role="button" style="margin-top:20px"  class="pull-left btn btn-primary"><i
            class="fa fa-fw fa-plus"></i>
        Add User
    </a>
  <form style="width:300px;float:right;" action="{{ url('admin/user/search') }}" method="GET">
        <div class="input-group">
            <input type="text" required value="{{ request('q') }}" class="form-control" name="q"
                   placeholder="Search User"> <span class="input-group-btn">
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
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile Number</th>
                    <th>Loyalty Tier</th>
                    <th>User Type</th>
                    <th>Date Created</th>
                    <th>Action</th>
                </tr>
            </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->first_name . ' ' . $user->surname ?? ''}}</td>
                        <td>{{ $user->email ?? ''}}</td>
                        <td>{{ $user->mobile_number ?? ''}}</td>
                        @if($user->tier_id == null)
                        <td>{{ 'N/A'}}</td>
                            @else
                            <td>{{ $user->loyaltyTier->name ?? ''}}</td>
                         @endif
                         <td>{{ $user->userType->name ?? ''}}</td>
                        <td>{{ $user->created_at ?? ''}}</td>
                        <td>
                            <a href="{{ url('/admin/user/readOnly') . '/' . $user->id }}" role="button"
                                class="btn btn-warning btn-sm">View</a>
                            <a href="{{ url('/admin/user/edit') . '/' . $user->id }}" role="button"
                               class="btn btn-primary btn-sm">Edit</a>
                            <a href="{{ url('/admin/user/delete') . '/' . $user->id }}" role="button"
                               name="deleteUser" id="deleteUser" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile Number</th>
                    <th>Loyalty Tier</th>
                    <th>User Type</th>
                    <th>Date Created</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    <div>{{ $users->appends(\Request::except('page'))->render()}}</div>
@endsection


