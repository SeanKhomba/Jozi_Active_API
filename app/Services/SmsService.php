<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SmsService {
    private $clientId = "20dae5d3-0eb7-4184-b2ef-a4e54a6f1c3f";
    private $apiKey = "kOuI4rA5cWu/uznDkwnYABb5Q5R6mdfF";
    private $token = "";

    public function __construct() {

       // $this->getToken();
        $db = DB::table( 'settings' )->where( 'setting_key', '=', 'sms_token' )->limit( 1 )->select( 'setting_value' )->get()->first();
        $this->token = $db->setting_value;
    }

    public function getToken() {
        $client       = new \GuzzleHttp\Client();
        $sendResponse = $client->request( 'GET', "https://rest.smsportal.com/v1/Authentication", [
            'headers'     => [
                'Authorization' => "Basic " . base64_encode( "{$this->clientId}:{$this->apiKey}" ),
                'Content-type'=>'application/json',
            ],
            'http_errors' => false
        ] );
        if ( $sendResponse->getStatusCode() == 200 ) {
            //dd(json_decode( $sendResponse->getBody()));
            $this->token = json_decode( $sendResponse->getBody() )->token;
            DB::statement( DB::raw( 'update settings set updated_at = now(), setting_value = "' . $this->token . '" where setting_key = "sms_token"' ) );
            return true;
        } else {
            return false;
        }
    }

    public function sendSms( $number, $message, $end = false ) {
        $client          = new \GuzzleHttp\Client();
        $sendRequestBody = json_decode( '{ "messages" : [ { "content" : "' . $message . '", "destination" : "' . $number . '" } ] }', true );
        $sendResponse    = $client->request( 'POST', "https://rest.smsportal.com/v1/bulkmessages", [
            'json'        => $sendRequestBody,
            'headers'     => [ 'Authorization' => 'Bearer ' . $this->token ],
            'http_errors' => false
        ] );

//        Log::debug($sendResponse->getBody());

        if ( $sendResponse->getStatusCode() == 200 ) {
            return true;
        } else {
            if ( $end ) {
                return false;
            } else {
                //get new token
                $this->getToken();
                return $this->sendSms( $number, $message, true );
            }
        }
    }
}
