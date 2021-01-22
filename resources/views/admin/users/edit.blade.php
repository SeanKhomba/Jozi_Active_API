@extends('adminlte::page')

@if(isset($create))
    @section('title', 'Create User')
@else
    @section('title', 'Edit User')

@endif
@section('content_header')
    @if(isset($create))
        <h1>Create User</h1>
    @endif
    @if(isset($edit))
        <h1>Edit User</h1>
    @endif
    @if(isset($readOnly))
    <h1>View User</h1>
    @endif
@endsection

@section('content')
    @include('admin.partials.status')
    @if(isset($create))
        <div class="card">
            <form role="form" method="POST" enctype="multipart/form-data" action="{{ URL::to('admin/user/save') }}">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">First Name</label>
                        <input name="first_name" value="{{old('first_name')}}" required type="text"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="surname">Surname</label>
                        <input name="surname" value="{{old('surname')}}" required type="text"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email_address">Email Address</label>
                        <input name="email" value="{{old('email')}}" required type="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="mobile_number">Mobile Number</label>
                        <input name="mobile_number" value="{{old('mobile_number')}}" required type="number" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input name="password" value="{{old('password')}}"
                               required type="password"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="user_type">User Type</label>
                        <select name="user_type" id="user_type" required class="form-control">
                            <option selected disabled value="">Select User Type</option>
                            @foreach ($user_types as $user_type)
                                <option name="user_type" id="user_type" class="list-group-item"
                                        value="{{ $user_type->id }}">{{ $user_type->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    <div class="box-footer">
                        <input type="submit" id="save" class="btn btn-primary" value="Save">
                    </div>
                </div>
            </form>
        </div>
    @endif


    @if(isset($edit))
        <div class="card">
            <form role="form" method="POST" enctype="multipart/form-data"
                  action="{{ URL::to('admin/user/update') }}">
                {{ csrf_field() }}
                <div class="card-body">
                    <input name="id" value="{{$user->id}}" required type="hidden" class="form-control">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input name="first_name" value="{{$user->first_name}}" required type="text"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="surname">Surname</label>
                        <input name="surname" value="{{$user->surname}}" required type="text"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="mobile_number">Mobile Number</label>
                        <input name="mobile_number" value="{{$user->mobile_number}}" required type="number" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email_address">Email Address</label>
                        <input name="email" value="{{$user->email}}" required type="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input name="password" type="password" class="form-control">
                    </div>
                    <div class="box-footer">
                        <input type="submit" id="save" class="btn btn-primary" value="Save">
                    </div>
                </div>
            </form>
        </div>
    @endif



    @if(isset($readOnly))
    <div class="card">
        <form role="form" method="GET" enctype="multipart/form-data"
              action="{{ URL::to('admin/users') }}">
            {{ csrf_field() }}
            <div class="card-body">
                <input name="id" value="{{$user->id}}" required type="hidden" class="form-control">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input readOnly name="first_name" value="{{$user->first_name}}" required type="text"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="surname">Surname</label>
                    <input readOnly name="surname" value="{{$user->surname}}" required type="text"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="mobile_number">Mobile Number</label>
                    <input readOnly name="mobile_number" value="{{$user->mobile_number}}" required type="number" class="form-control">
                </div>
                <div class="form-group">
                    <label for="email_address">Email Address</label>
                    <input readOnly name="email" value="{{$user->email}}" required type="email" class="form-control">
                </div>
                @if($user->user_type_id == 2)
                <div class="form-group">
                    <label for="loyalty_tier">Loyalty Tier</label>
                    @if($user->tier_id != null)
                    <input readOnly name="loyalty_tier" value="{{$user->loyaltyTier->name}}" required type="text" class="form-control">
                    @else
                    <input readOnly name="loyalty_tier" value="N/A" required type="text" class="form-control">
                    @endif
                </div>
                @endif
                <div class="form-group">
                    <label for="user_type">User Type</label>
                    <select readOnly name="user_type" id="user_type" required class="form-control">
                        <option selected disabled value="">Select User Type</option>
                        @foreach ($user_types as $user_type)
                            <option name="user_type" id="user_type" class="list-group-item"
                                    value="{{ $user_type->id }}" @if(isset($user)){{ $user->user_type_id == $user_type->id  ? 'selected' : ''}}@endif >{{ $user_type->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="box-footer">
                        <input type="submit"  id="save" class="btn btn-primary" value="Back">
                    </div>
            </div>
        </form>
    </div>
@endif

@endsection


