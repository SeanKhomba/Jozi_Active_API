@extends('adminlte::page')

@if(isset($create))
    @section('title', 'Create Event')
@endif
@if(isset($edit))
    @section('title', 'Edit Event')
@endif

@if(isset($readonly))
    @section('title', 'View Event')
@endif

@section('content_header')
    @if(isset($create))
        <h1>Create Event</h1>
    @endif
    @if(isset($edit))
        <h1>Edit Event</h1>
    @endif
    @if(isset($readonly))
    <h1>View Event</h1>
@endif
@endsection



@section('content')
    @include('admin.partials.status')
    @if(isset($create))
        <div class="card">
            <div class="card-body">

            <form role="form" method="POST" id="event" enctype="multipart/form-data"
                  action="{{ URL::to('admin/event/save') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="name">Event Name</label>
                    <input name="event_name" id="event_name" value="{{old('event_name')}}" required type="text"
                           class="form-control">
                </div>

                <div class="form-group">
                    <label for="categories">Select Category</label>
                    <select name="category" id="category" required class="form-control">
                        <option selected disabled value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option name="category" id="category" class="list-group-item"
                                    value="{{ $category->id }}">{{ $category->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input id="price" value="{{old('price')}}" type="text" name="price"
                           required class="form-control" autocomplete="false">
                </div>
                <div class="form-group">
                    <label for="quantity_available">Quantity Available</label>
                    <input id="quantity_available" value="{{old('quantity_available')}}" type="number" name="quantity_available"
                           required class="form-control" autocomplete="false">
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input id="searchTextField" value="{{old('location')}}" type="text" name="physical_address"
                           required class="form-control" autocomplete="false">
                </div>
                <div class="form-group">
                    <label for="event_description">Event Description</label>
                    <textarea name="event_description" id="event_description" class="form-control"
                              rows="5"></textarea>
                </div>

                <div class="form-group">
                    <label for="date">Date</label>
                    <input id="date" value="{{old('date')}}" type="date" name="date"
                           required class="form-control" autocomplete="false">
                </div>

                <div class="form-group">
                    <label for="start_time">Start Time</label>
                    <input id="start_time" value="{{old('start_time')}}" type="time" name="start_time"
                           required class="form-control" autocomplete="false">
                </div>

                <div class="form-group">
                    <label for="end_time">End Time</label>
                    <input id="end_time" value="{{old('end_time')}}" type="time" name="end_time"
                           required class="form-control" autocomplete="false">
                </div>
                <div class="form-group">
                    <label for="images">Image(s)</label><br>
                    <input type="file" name="images[]" style="display: none;"  id="file-input" multiple accept="image/x-png,image/gif,image/jpeg"  />
                    <input type="button" value="Choose Images" onclick="document.getElementById('file-input').click();" />
                    <input type="text" name="imagesToRemove[]" id="imagesToRemove" hidden/>
                         <span class="text-danger">{{ $errors->first('image') }}</span>
               </div>
                <div id="thumb-output"></div><br>

                <div class="form-group">
                    <label for="active">Is Active</label><br>
                        <label>
                          <input type="radio" name="active" value="yes" id="is_active" autocomplete="off" checked> Yes
                        </label>
                        <label>
                          <input type="radio" name="active"  value="no"  id="not_active" autocomplete="off"> No
                        </label>
                   
                </div>

                <br>
                <div class="box-footer">
                    <input type="submit" id="save" class="btn btn-primary" value="Save">
                </div>
            </form>
        </div>
    </div>
    @endif

    @if(isset($edit))
        <div class="card">
            <div class="card-body">

            <form role="form" method="POST" id="event" enctype="multipart/form-data"
                  action="{{ URL::to('admin/event/update') }}">
                {{ csrf_field() }}
                <input name="id" value="{{$event->id}}" required type="hidden" class="form-control">
                <div class="form-group">
                    <label for="name">Event Name</label>
                    <input name="event_name" id="event_name" value="{{ $event->name }}" required type="text"
                           class="form-control">
                </div>

                <div class="form-group">
                    <label for="categories">Select Category</label>
                    <select name="category" id="category" required class="form-control">
                        <option selected disabled value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option {{ $category->id == $event->id  ? 'selected' : ''}} name="category" id="category" class="list-group-item"
                                    value="{{ $category->id }}">{{ $category->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input id="price"  value="{{ $event->price }}"  type="text" name="price"
                           required class="form-control" autocomplete="false">
                </div>
                <div class="form-group">
                    <label for="quantity_available">Quantity Available</label>
                    <input id="quantity_available" value="{{ $event->quantity_available }}" type="number" name="quantity_available"
                           required class="form-control" autocomplete="false">
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input id="searchTextField" value="{{ $event->location }}" type="text" name="physical_address"
                           required class="form-control" autocomplete="false">
                </div>
                <div class="form-group">
                    <label for="event_description">Event Description</label>
                    <textarea name="event_description" id="event_description" class="form-control"
                              rows="5">{{ $event->description }}</textarea>
                </div>

                <div class="form-group">
                    <label for="date">Date</label>
                    <input id="date" value="{{ \Carbon\Carbon::parse($event->date)->Format('yy-m-d') }}" type="date" name="date"
                           required class="form-control" autocomplete="false">
                </div>

                <div class="form-group">
                    <label for="start_time">Start Time</label>
                    <input id="start_time" value="{{ $event->start_time }}" type="time" name="start_time"
                           required class="form-control" autocomplete="false">
                </div>

                <div class="form-group">
                    <label for="end_time">End Time</label>
                    <input id="end_time" value="{{ $event->end_time }}" type="time" name="end_time"
                           required class="form-control" autocomplete="false">
                </div>
                <div class="form-group">
                    <label for="images">Image(s)</label><br>
                    <input type="file" name="images[]" style="display: none;"  id="file-input" multiple accept="image/x-png,image/gif,image/jpeg"  />
                    <input type="button" value="Choose Images" onclick="document.getElementById('file-input').click();" />
                    <input type="text" name="imagesToRemove[]" id="imagesToRemove" hidden/>
                         <span class="text-danger">{{ $errors->first('image') }}</span>
               </div>
                <div id="thumb-output"></div><br>

                <div class="form-group">
                        <label for="image">Previously Uploaded Image(s)</label><br>
                       @foreach($images as $image)
                       <table id="uploaded-img" class="table table-bordered">
                        <td  class="form-group col-md-3">
                            <input type="text" name="recentlyUploadImages[]" value="{{ url( $image->media_path) }}" id="recentlyUploadImages" hidden/>
                            <img style="max-width:360px" src="{{ url( $image->media_path) }}" class="img-thumbnail zoom" alt="">
                                <button type="button" style="float: right" id="remove-image" class="close" aria-label="Close">
                                    <i id="remove-image" class="fa fa-window-close"></i> Remove
                                </button>
                            </td>
                        </table>
                        @endforeach
               </div>
              
                <br>

                <div class="form-group">
                    <label for="active">Is Active</label><br>
                        <label>
                          <input type="radio" name="active" value="yes" id="is_active" autocomplete="off" {{ ($event->active == 1 ? ' checked' : '')}}> Yes
                        </label>
                        <label>
                          <input type="radio" name="active" value="no"  id="not_active" autocomplete="off" {{ ($event->active == 0 ? ' checked' : '')}}> No
                        </label>
                   
                </div>

                <br>
                <div class="box-footer">
                    <input type="submit" id="save" class="btn btn-primary" value="Update">
                </div>
            </form>
        </div>
    </div>
    @endif

    @if(isset($readonly))
        <div class="card">
            <div class="card-body">

            <form role="form" method="GET" id="event" enctype="multipart/form-data"
                  action="{{ URL::to('admin/events') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="name">Event Name</label>
                    <input name="event_name" id="event_name" readonly value="{{ $event->name }}" required type="text"
                           class="form-control">
                </div>

                <div class="form-group">
                    <label for="categories">Select Category</label>
                    <select  readonly name="category" id="category" required class="form-control">
                        <option selected disabled value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option name="category" id="category" class="list-group-item"
                                    value="{{ $category->id }}">{{ $category->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input id="price"  readonly value="{{ $event->price }}"  type="text" name="price"
                           required class="form-control" autocomplete="false">
                </div>
                <div class="form-group">
                    <label for="quantity_available">Quantity Available</label>
                    <input id="quantity_available" readonly value="{{ $event->quantity_available }}" type="number" name="quantity_available"
                           required class="form-control" autocomplete="false">
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input id="searchTextField" readonly value="{{ $event->location }}" type="text" name="physical_address"
                           required class="form-control" autocomplete="false">
                </div>
                <div class="form-group">
                    <label for="event_description">Event Description</label>
                    <textarea name="event_description" readonly id="event_description" class="form-control"
                              rows="5">{{ $event->description }}</textarea>
                </div>

                <div class="form-group">
                    <label for="date">Date</label>
                    <input id="date"  readonly value="{{ \Carbon\Carbon::parse($event->date)->Format('yy-m-d') }}" type="date" name="date"
                           required class="form-control" autocomplete="false">
                </div>

                <div class="form-group">
                    <label for="start_time">Start Time</label>
                    <input id="start_time" readonly value="{{ $event->start_time }}" type="time" name="start_time"
                           required class="form-control" autocomplete="false">
                </div>

                <div class="form-group">
                    <label for="end_time">End Time</label>
                    <input id="end_time" readonly value="{{ $event->end_time }}" type="time" name="end_time"
                           required class="form-control" autocomplete="false">
                </div>
                <div class="form-group">
                    <label for="images">Image(s)</label><br>
                    
               </div>
                <div id="thumb-output">
                    @foreach($images as $image)
                    <img class="img-thumbnail" style="max-width:380px" src="{{ url( $image->media_path) }}"/>
                    @endforeach
                </div>
                <br>

                <div class="form-group">
                    <label for="active">Is Active</label><br>
                        <label>
                          <input type="radio" name="active" disabled value="yes" id="is_active" autocomplete="off" {{ ($event->active == 1 ? ' checked' : '')}}> Yes
                        </label>
                        <label>
                          <input type="radio" name="active" disabled value="no"  id="not_active" autocomplete="off" {{ ($event->active == 0 ? ' checked' : '')}}> No
                        </label>
                   
                </div>

                <br>
                <div class="box-footer">
                    <input type="submit" id="save" class="btn btn-primary" value="Back">
                </div>
            </form>
        </div>
    </div>
    @endif
    
@endsection


