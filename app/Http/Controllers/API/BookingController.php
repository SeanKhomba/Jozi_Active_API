<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Event;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\UserType;
use App\Models\Tier;
use App\Services\SmsService;

class BookingController extends Controller
{
    public $successStatus = 200;

    public function captureBooking()
    {
        $data = \request()->all();
       $loggedUser = auth('api')->user();
        $loggedUser->id;
        $data['transaction_id'] = 'randomtransid';
        $rules = array(
            'event_id' => 'required',
            'quantity' => 'required',
           
            'transaction_id' => 'required',
           
        );
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 'Error', "error" => $validator->errors()->first()]);
        }
        try{
            
            DB::beginTransaction();
            $events = Event::where('id', $data['event_id'])->where('quantity_available' , '!=' , '0')->get();

            $booking = Booking::Create([
                'event_id' => $data['event_id'],
                'user_id' => $loggedUser->id,
                'quantity' => $data['quantity'],
            ]);

            
            $payment = Payment::Create([
                'transaction_id' => $data['transaction_id'],
                'payment_type_id' => 1,
                'user_id' => $loggedUser->id,
                'amount' => $events[0]->price,
                'booking_id' => $booking->id,
            ]);

            Event::where('id', $data['event_id'])->update([
                'quantity_available' => DB::raw('quantity_available-'. $data['quantity'])
            ]);

            $user = User::where('id', $loggedUser->id)->get();
            $bookingCount = Booking::where('user_id' , $loggedUser->id)->get()->count();

            $tiers = Tier::get();

            foreach($tiers as $tier){
                if($tier->minimum_bookings == $bookingCount){
                    User::where('id', $loggedUser->id)->update([
                    'tier_id' => $tier->id
                    ]);
                }

             $success = collect(['booking' => $booking]);

            }
            DB::commit();   
             return response()->json(['status' => 'success','data' => $success], $this->successStatus);
         } catch(\Exception $e){
            DB::rollback();
            return response()->json(['status' => 'Error', 'msg' => $e->getMessage()], 400);
        }
    }
 
}
