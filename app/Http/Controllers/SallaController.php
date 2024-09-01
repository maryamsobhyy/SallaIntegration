<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SallaController extends Controller
{
    public function easymode(Request $request){
        logger($request->all());
        $data=$request->all();
        // $store=Http::withToken($request->data['access_token'])->get('https://api.salla.dev/admin/v2/oauth2/user/info');
        // return to salla response 200
        return response([
            'sucess'=>'true',
            'message'=>'we receive our event',
            'data'=>$data,
            // 'store'=>$store->body(),
        ],200);
    }
}
