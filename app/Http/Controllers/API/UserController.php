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
use App\Models\UserType;
use App\Models\Payment;
use App\Models\PasswordReset;
use App\Services\SmsService;

class UserController extends Controller
{
    public $successStatus = 200;

    public function login()
    {

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'message' => 'Your username and/or password do not match our records.',
                    'type' => 'form'
                ], 401);
            }

            $success = collect(['user' => $user, 'token' => $user->createToken('joziActive')->accessToken]);

            return response()->json(['success' => $success], $this->successStatus);
        } else {
            return response()->json([
                'message' => 'Your username and/or password do not match our records.',
                'type' => 'form'
            ], 401);
        }
    }

     public function register(Request $request)
    {
        $request['email'] = strtolower($request['email']);


        $rules = [
            'email' => 'required|unique:users',
        ];
        $customMessages = [
            'email.unique' => 'This email is already registered'
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
             return response()->json(['status' => 'Error', "msg" => $validator->errors()->first()]);
        }

        $data = $request->all();

       // return response()->json(['data' => $data], $this->successStatus);

    
        try{
            DB::beginTransaction();
        $user = User::create([
            'user_type_id' => 2,
            'first_name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'mobile_number' => $data['phone'],
            'password' => bcrypt($data['password']),
        ]);
        DB::commit();
        $success = collect(['user' => $user,'token' => $user->createToken('joziActive')->accessToken]);

        return response()->json(['status' => 'Success','data' => $success], $this->successStatus);
    } catch(\Exception $e){
        DB::rollback();
        return response()->json(['status' => 'Error', 'msg' => $e->getMessage()], 400);
    }

    }

    public function forgotPassword()
    {

       $mobile_number = \request()->mobile_number;
      // $mobile_number = "0736666631";
        $user = User::where('user_type_id', 2)->where('mobile_number', $mobile_number)->first();

        if ($user) {
            $success = collect(['user' => $user, 'token' => $user->createToken('joziActive')->accessToken]);
            $resetToken = Str::random(32);
            PasswordReset::insert([
                'email' => $user->email,
                'mobile_number'=> $mobile_number,
                'token' => $resetToken,
                'created_at' => Carbon::now()
            ]);
            $sms  = new SmsService();
            $sms->sendSms( $mobile_number, "We received a password reset request. Please follow the link to reset your password: http://joziactiveapi.com/$resetToken " );
            return response()->json(['success' => $success], $this->successStatus);
        } else {
            return response()->json(['error' => 'This Mobile Number does not match our records'], 401);
        }
    }

    public function getMobileNumber()
    {
        $token = \request()->token;

        $mobile_number = PasswordReset::where('token', $token)->pluck('mobile_number');

        $success = collect(['mobile_number' => $mobile_number]);
        return response()->json(['success' => $success], $this->successStatus);
    }

    public function passwordReset()
    {

        $mobile_number = \request()->mobile_number;
        $password = \request()->password;
        $resetToken = \request()->resetToken;

        $getToken = PasswordReset::where('mobile_number', $email)->pluck('token')->last();
        $tokenTime = PasswordReset::where('mobile_number', $email)->pluck('created_at')->last();

        $currentTime = Carbon::now();

        $timeDifference = $tokenTime->diffInMinutes($currentTime);


        if ($timeDifference > 120) {
            return response()->json(['error' => 'This link has expired, please send another reset request.'], 401);
        } else {

            if ($resetToken != $getToken) {
                return response()->json(['error' => 'The link is not correct, please try again.'], 401);
            } else {

                $user = User::where('mobile_number', $mobile_number)->update([
                    'password' => bcrypt($password)
                ]);

                $success = collect(['user' => $user]);
                return response()->json(['success' => $success], $this->successStatus);
            }
        }
    }

    public function getUserAccount()
    {
        $data = \request()->all();

        $user = User::where('id', $data['user_id'])->get();

        $success = collect(['user' => $user]);

        return response()->json(['success' => $success], $this->successStatus);
    }

    public function updateAccount()
    {
        $data = \request();

        if (isset($data['password'])) {
            $user = User::where('id', $data['user_id'])->update([
                'first_name' => $data['first_name'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'mobile_number' => $data['mobile_number'],
                'password' => bcrypt($data['password']),
            ]);

            $success = collect(['user' => $user]);

            return response()->json(['success' => $success], $this->successStatus);
        } else {
            $user = User::where('id', $data['user_id'])->update([
                'first_name' => $data['first_name'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'mobile_number' => $data['mobile_number'],
            ]);
            $success = collect(['user' => $user]);

            return response()->json(['success' => $success], $this->successStatus);
        }
    }


    public function checkAccountStatus()
    {
        $data = \request();

        $payments = Payment::where('user_id', $data['user_id'])->where('payment_type_id' , '3')->get()->last();

        if($payments == null){
            $success = collect(['status' => 'not_paid']);
            return response()->json(['success' => $success], $this->successStatus);
        }

        $now = Carbon::now();

        if($payments->created_at->diff($now)->days > 30 ) {
            $success = collect(['status' => 'not_paid']);
            return response()->json(['success' => $success], $this->successStatus);      
          } else {
            $success = collect(['status' => 'paid']);
            return response()->json(['success' => $success], $this->successStatus);     
          }
    }

    public function captureAccountActivation()
    {
        $data = \request()->all();
        $payment = Payment::Create([
            'transaction_id' => $data['transaction_id'],
            'payment_type_id' => 3,
            'user_id' => $data['user_id'],
            'amount' => $data['amount'],
        ]);

        $success = collect(['payment' => $payment]);
        return response()->json(['success' => $success], $this->successStatus);     
    }


    public function symlink(){
        if(!file_exists(public_path('storage'))) {
            \App::make('files')->link(storage_path('app/public'), public_path('storage'));
        }
    }


}
