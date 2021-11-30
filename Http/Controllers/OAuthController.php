<?php

namespace Modules\OAuth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \App\User;
use Illuminate\Support\Facades\Auth;


class OAuthController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $settings = \Option::getOptions([
            'oauth.active',
            'oauth.client_id',
            'oauth.client_secret',
            'oauth.token_url',
            'oauth.user_url',
        ]);

        $ch = curl_init($settings['oauth.token_url']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query([
                'client_id' => $settings['oauth.client_id'],
                'client_secret'=> $settings['oauth.client_secret'],
                'grant_type' => 'authorization_code',
                'code' => $request->get('code'),
                'redirect_uri' => route('oauth_callback'),
            ]),
            CURLINFO_HEADER_OUT => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
            ],
        ]);
        $data = json_decode(curl_exec($ch), true);
        $accessToken = $data['access_token'];

        curl_setopt_array($ch, [
            CURLOPT_URL => $settings['oauth.user_url'],
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $accessToken,
            ],
        ]);
        $data = json_decode(curl_exec($ch), true);

        $user = User::where('email','=', $data['email'])->first();
        Auth::login($user);

        return redirect($request->session()->get('url.intended', '/'));
    }
}
