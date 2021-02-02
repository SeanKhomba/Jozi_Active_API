<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\UserType;
use App\Services\SmsService;

class EventController extends Controller
{
    public $successStatus = 200;
    public $date;

    
    public function getEvents()
    {
        $data = \request()->all();
        $this->date =  Carbon::Parse($data['date']);
       // $events = Event::where('category_id', $data['category_id'])->where('date' , $data['date'])->with('eventMedia')->get();

        $events = Event::where('category_id', $data['category_id'])->where(function($query){
        return $query->where('date' , $this->date)->where('quantity_available' , '!=' , '0');
        })->with('eventMedia')->get();
        
        $success = collect(['events' => $events]);

        return response()->json(['success' => $success], $this->successStatus);
    }

    public function getEventsCategory(){
      $eventCategories =  EventCategory::all();
        return response()->json(array('status' => 'OK', 'data' => $eventCategories->toArray()), 200); 
    }

    public function getAllUpcomingEvent(){

    }


    public function getEventsByCat()
    {
        $data = \request()->all();
        if(isset($data['date'])){
                $this->date =  Carbon::Parse($data['date']);
        }
       // $events = Event::where('category_id', $data['category_id'])->where('date' , $data['date'])->with('eventMedia')->get();

        $events = Event::where('category_id', $data['category_id'])->where(function($query){
            if(isset($data['date'])){
                $query->where('date' , $this->date);
            }
        return $query->where('quantity_available' , '!=' , '0');
        })->with('eventMedia')->get();
        
      //  $success = collect(['events' => $events]);

        return response()->json(['status' => 'OK', 'data' => $events->toArray()], $this->successStatus);
    }

    public function getEventById()
    {
        $data = \request()->all();
       

        $events = Event::where('id', $data['id'])->where('quantity_available' , '!=' , '0')->get();
        //print_r($events);
      //  $success = collect(['events' => $events]);

        return response()->json(['status' => 'OK', 'data' => $events->toArray()], $this->successStatus);
    }



}
