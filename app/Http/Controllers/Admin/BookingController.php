<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserType;
use App\Models\Booking; 
use App\Models\Event; 
use Illuminate\Pagination\LengthAwarePaginator;

class BookingController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function index()
    {
        return view('admin.bookings.index', ['bookings' => Booking::where('deleted_at' , null)->simplePaginate(20)]);
    }

    public function delete($id)
    {
        Booking::where('id' , $id)->delete();
        return redirect('admin/bookings')->with('success', 'Booking Deleted!');
    }
  
    
    public function search()
    {
        $data = \request()->all();
		unset($data['_token']);

		$q = $data['q'];

		$events = Event::where('deleted_at' , null)->where('name','LIKE','%'.$q.'%')->pluck('id');

        $clients = User::where('deleted_at' , null)->where('first_name','LIKE','%'.$q.'%')->pluck('id');
        
        $bookings = collect();

        if(!$clients->isEmpty()){
            $bookings = Booking::whereIn('user_id' , $clients)->simplePaginate(20);
        }

        if(!$events->isEmpty()){
            $bookings = Booking::whereIn('event_id' , $events)->simplePaginate(20);
        }

        if($bookings->isEmpty()){
			return back()->with('warning', 'No Details found. Try to search again !');
		}else {
            \request()->session()->now('success', count($bookings->flatten(1)->unique()). ' results found');
			return view('admin.bookings.index', ['bookings' => $bookings]);
		}
    }
}
