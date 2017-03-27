<?php

namespace App\Http\Controllers;

use App\Client;
use App\Salon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class AdminController extends Controller
{
    public function index(){

    }

    public function getSalon(){
        $admin = DB::table('admin')
            ->select('name', 'email', 'salon_id')
            ->where('id', session('user'))->first();
        $admin->salon = DB::table('salon')
            ->select('name', 'mail')
            ->where('id', $admin->salon_id)->first();
        $admin->bids = DB::table('bids')
            ->leftJoin('clients', 'clients.id', 'bids.user_id')
            ->leftJoin('services', 'services.id', 'bids.usluga_id')
            ->leftJoin('Stage', 'Stage.id', 'bids.stage_id')
            ->select('clients.*', 'services.name', 'Stage.name as status')
            ->where('bids.salon_id', $admin->salon_id)->get()->toArray();
        $admin->useBids = DB::table('bids_no_regist_user')
            ->leftJoin('services', 'services.id', 'bids_no_regist_user.usluga_id')
            ->select('bids_no_regist_user.*', 'services.name')
            ->where('salon_id', $admin->salon_id)->get()->toArray();
        $admin->users = DB::table('clients')
            ->Join('bids', 'bids.user_id', 'clients.id')
            ->where('bids.salon_id', $admin->salon_id)
            ->select('clients.id', 'firstname', 'lastname', 'telephone', 'ball', DB::raw('count(bids.id) as cnt'))
            ->GroupBy('clients.id', 'firstname', 'lastname', 'telephone', 'ball')
            ->get()->toArray();


        return view('admin.home', ['admin'=>$admin]);
    }

    public function showUpdateUser(){
        $id = Input::get('id');
        $user = DB::table('clients')->where('id', $id)->first();
        $html = ['fname' => $user->firstname, 'lname' => $user->lastname, 'telephone' => $user->telephone, 'id' => $id];
        return json_encode($html);
    }

    public function updateUser(Request $request){
        $fname = $request->input('firstname');
        $lname = $request->input('lastname');
        $telephone = $request->input('telephone');
        $id = $request->input('id');

        Client::where('id', $id)
            ->update(['firstname' => $fname, 'lastname' => $lname, 'telephone' => $telephone]);

        return redirect('/home');
    }
}
