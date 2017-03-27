<?php

namespace App\Http\Controllers;

use App\Bid;
use App\BidsNoUser;
use App\Client;
use App\Mail\Reminder;
use App\Salon;
use App\Service;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\In;

class IndexController extends Controller
{



    public function login(){
        $header = array (
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'utf-8'
        );

        $phone = IndexController::phone(Input::get('phone'));
        if(strlen($phone) == 10){
            $client = Client::where('telephone', $phone)->select('id', 'ball', 'firstname', 'lastname', 'password')->first();
            if($client != null){
                if($client->password == Input::get('pass')){
                    return response()->json([
                        'errorCode' => 0,
                        'id' => $client->id,
                        'firstname' => $client->firstname,
                        'lastname' => $client->lastname,
                        'ball' => $client->ball,
                    ], 200,  $header, JSON_UNESCAPED_UNICODE);
                }else{
                    return response()->json([
                        'errorCode' => 1,
                        'errorMessage' => 'ошибка'
                    ], 200,  $header, JSON_UNESCAPED_UNICODE);
                }
            }else{
                return response()->json([
                    'errorCode' => 1,
                    'errorMessage' => 'ошибка'
                ], 200,  $header, JSON_UNESCAPED_UNICODE);
            }
        }
        return response()->json([
            'errorCode' => 1,
            'errorMessage' => 'ошибка'
        ], 200,  $header, JSON_UNESCAPED_UNICODE);
    }


    public function reg_client(){
        $header = array (
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'utf-8'
        );

        Input::get('fname') ? $fname = Input::get('fname') : $fname = '';
        Input::get('lname') ? $lname = Input::get('lname') : $lname = '';
        $salon_id = Input::get('salon_id');
        $pass = Input::get('pass');
        $phone = IndexController::phone(Input::get('phone'));


        $salon_cnt = Salon::where('id', $salon_id)->count();
        if($salon_cnt > 0 and strlen($phone) == 10){
            $isLogin = Client::where('telephone', $phone)->count();
            if($isLogin == 0){
                $client = new Client();
                $client->firstname = $fname;
                $client->lastname = $lname;
                $client->telephone = $phone;
                $client->password = $pass;
                $client->salon_id = $salon_id;
                $client->save();

                return response()->json([
                    'errorCode' => 0,
                    'id' => $client->id,
                    'ball' => $client->ball,
                    'firstname' => $client->firstname,
                    'lastname' => $client->lastname,
                ], 200,  $header, JSON_UNESCAPED_UNICODE);

            }else{
                return response()->json([
                    'errorCode' => 1,
                    'errorMessage' => 'логин уже существует'
                ], 200,  $header, JSON_UNESCAPED_UNICODE);
            }
        }
        return response()->json([
            'errorCode' => 2,
            'errorMessage' => 'ошибка'
        ], 200,  $header, JSON_UNESCAPED_UNICODE);
    }



    public function open_bid(){
        $header = array (
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'utf-8'
        );
        $user_id = Input::get('user_id');
        $usluga_id = Input::get('usluga_id');
        $salon_id = Input::get('salon_id');
        if(Client::where('id', $user_id)->count() > 0){
            if(Service::where('id', $usluga_id)->count() > 0){
                if(Salon::where('id', $salon_id)->count() > 0){
                    $bid = new Bid();
                    $bid->user_id = $user_id;
                    $bid->usluga_id = $usluga_id;
                    $bid->salon_id = $salon_id;
                    $bid->stage_id = 1;
                    $bid->date = date('Y:m:d');
                    $bid->save();

                    $link = 'bidStatus?id='.$bid->id.'&salon='.$salon_id;
                    $client = Client::where('id', $user_id)->select('telephone', 'firstname', 'lastname')->first();
                    $salon = Salon::where('id', $salon_id)->select('mail', 'name')->first();
                    Mail::send('mail.reminder', ['link' => $link, 'phone' => $client->telephone,
                        'name' => $client->firstname], function($message) use ($salon)
                    {
                        $message->to($salon->mail, $salon->name)->subject('Привет!');
                    });

                    return response()->json([
                        'errorCode' => 0,
                        'errorMessage' => 'Ошибки нет. Успех!'
                    ], 200,  $header, JSON_UNESCAPED_UNICODE);
                }
            }
        }


    }

    public function close_bid(){
        $header = array (
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'utf-8'
        );
        $bid_id = Input::get('bid_id');
        $isUpdate = Bid::where('id', $bid_id)->update(['stage_id' => 3]);
        if($isUpdate){
            $bid = Bid::where('id', $bid_id)->select('user_id', 'usluga_id')->first();
            $user_id = $bid->user_id;
            $usluga_id = $bid->user_id;
            $service = Service::where('id', $usluga_id)->select('ball')->first();
            $client = Client::where('id', $user_id)->select('ball')->first();
            $ball = $client->ball + $service->ball;
            Client::where('id', $user_id)->update(['ball' => $ball]);
            return response()->json([
                        'errorCode' => 0,
                        'errorMessage' => 'Ошибки нет. Успех!'
            ], 200,  $header, JSON_UNESCAPED_UNICODE);
        }else{
           return response()->json([
               'errorCode' => 1,
               'errorMessage' => 'Ошибкa!'
           ], 200,  $header, JSON_UNESCAPED_UNICODE);
        }
    }

    public function bid_no_user(){
        $header = array (
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'utf-8'
        );
        Input::get('phone') ? $phone = Input::get('phone') : $phone = '';
        Input::get('name') ? $name = Input::get('name') : $name = '';
        $usluga_id = Input::get('usluga_id');
        $salon_id = Input::get('salon_id');

        $phone = IndexController::phone($phone);
        if(strlen($phone) == 10){
            if(Service::where('id', $usluga_id)->count() > 0){
                if(Salon::where('id', $salon_id)->count() > 0){
                    $salon = Salon::where('id', $salon_id)->select('mail', 'name')->first();
                    $nbid = new BidsNoUser();
                    $nbid->telephone = $phone;
                    $nbid->firstname = $name;
                    $nbid->usluga_id = $usluga_id;
                    $nbid->salon_id = $salon_id;
                    $nbid->date = Date('Y:m:d');
                    $nbid->save();

                    if($nbid->id > 0){
                        $link = '/admin';
                        Mail::send('mail.reminder', ['link' => $link, 'phone' => $phone,
                            'name' => $name], function($message) use ($salon)
                        {
                            $message->to($salon->mail, $salon->name)->subject('новый заказ');
                        });
                        return response()->json([
                            'errorCode' => 0,
                            'errorMessage' => 'Ошибки нет. Успех!'
                        ], 200,  $header, JSON_UNESCAPED_UNICODE);
                    }
                }
            }
        }
        return response()->json([
            'errorCode' => 1,
            'errorMessage' => 'ошибка'
        ], 200,  $header, JSON_UNESCAPED_UNICODE);
    }


    public function bidStatus(){
        $id_bid = Input::get('id');
        $id_salon = Input::get('salon');

        Bid::where('id', $id_bid)->where('salon_id', $id_salon)->update(['stage_id' => 2]);

        dd('ok');
    }

     function testMail(){
         $mail = 'sultan_1993@list.ru';

        dd('ok');
    }

    private function phone($phone){
        $phone = preg_replace('![^0-9]+!', '', $phone);
        if(strlen($phone) == 11) {
            if (substr($phone, 0, 2) == '77' or substr($phone, 0, 2) == '87') {
                $phone = substr($phone, 1, 10);
            }
        }
        return $phone;
    }
}
