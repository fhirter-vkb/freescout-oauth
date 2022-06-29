<?php

namespace Modules\OAuth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


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

        $tokenCh = curl_init($settings['oauth.token_url']);
        curl_setopt_array($tokenCh, [
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
        $tokenData = json_decode(curl_exec($tokenCh), true);
        $accessToken = $tokenData['access_token'];

        $userCh = curl_init($settings['oauth.user_url']);
        curl_setopt_array($userCh, [
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $accessToken,
            ],
        ]);
        $userData = json_decode(curl_exec($userCh), true);

        $name = $this->getName($userData['name']);

        $user = User::firstOrCreate([
            'email' => $userData['email']
            ],
            [
                'name'          => $userData['name'],
                'first_name'    => $name['first'],
                'last_name'     => $name['last'],
                'email'         => $userData['email'],
                'password'      => Str::random(26)
            ]
        );

        Auth::login($user);

        return redirect($request->session()->get('url.intended', '/'));
    }

    private function getName($nameString)
    {
        $nameArray = explode(" ", $nameString);
        if ($nameArray && (count($nameArray) == 2)) {
            $name['first'] = $nameArray[0];
            $name['last'] = $nameArray[1];
        } else {
            $name['first'] = $nameString;
            $name['last'] = '';
        }
        return $name;
    }
}
