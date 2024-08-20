<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Store;
use App\Models\OauthToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function auth()
    {
        $data = [
            'client_id' => config('salla.client_id'),
            'client_secret' => config('salla.client_secret'),
            'response_type' => 'code',
            'scope' => 'offline_access',
            'redirect_url' => config('salla.callback_url'),
            'state' => rand(11111111, 99999999),

        ];
        $query = http_build_query($data);
        return redirect(config('salla.auth_url') . '?' . $query);
    }
    public function callback(Request $request)
    {
        $data = [
            'client_id' => config('salla.client_id'),
            'client_secret' => config('salla.client_secret'),
            'code' => $request->code,
            'scope' => 'offline_access',
            'redirect_url' => config('salla.callback_url'),
            'state' => rand(11111111, 99999999),
            'grant_type' => 'authorization_code',
        ];
        OauthToken::updateOrCreate(
            ['client_id' => config('salla.client_id')],
            [
                'client_secret' => config('salla.client_secret'),
                'code' => $request->code,
                'scope' => 'offline_access',
                'redirect_url' => config('salla.callback_url'),
                'state' => $request->state,
                'grant_type' => 'authorization_code',
            ]
        );
        $response = Http::asForm()->post(config('salla.token_url'), $data);
        $jsonresponse = json_decode($response->body());
        if ($response->successful()) {
            $url = config('salla.base_api_url') . '/store/info';
            $store_information = Http::withToken($jsonresponse->access_token)->acceptJson()->get($url);
            Store::updateOrCreate([
                'access_token' => $jsonresponse->access_token,
                'refresh_token' => $jsonresponse->refresh_token,
                'client_id'=>config('salla.client_id'),
                'expires_in' => Carbon::now()->addSeconds($jsonresponse->expires_in)->toDateTimeString(),
            ]);

            return $store_information;
        }

        else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to fetch store information',
            ]);
        }
    }
}
