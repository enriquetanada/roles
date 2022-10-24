<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contacts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\Passport;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;

use Hash;
use Log;
use Session;
use Request;
use GuzzleHttp\Client;

use App\User;

class OAuthHelper extends Authenticatable
{
    use HasApiTokens, Notifiable;

    private $userClientToken = '4hleIwgP7ZbWyLIRklsdUeoFx9QuOpGNTBn3kpFN';

    public function generateUserToken($email, $password)
    {
        $client = new Client();
        try {
            $form_params = [
                'grant_type' => 'password',
                'client_id' => 3,
                'client_secret' => $this->userClientToken,
                'username' => $email,
                'password' => $password,
                'scope' => '',
            ];
            $response = $client
                ->post(url('/oauth/token'), [
                    'form_params' => $form_params,
                ])
                ->getBody()
                ->getContents();
            return $response;
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
        }
    }

    public function refreshUserToken($refresh_token)
    {
        $client = new Client();
        $response_obj = new \stdClass();
        try {
            $response = $client
                ->post(url('oauth/token'), [
                    'form_params' => [
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $refresh_token,
                        'client_id' => 3,
                        'client_secret' => $this->userClientToken,
                        'scope' => '',
                    ],
                ])
                ->getBody()
                ->getContents();
            $response_obj->response = $response;
            $response_obj->error = false;
        } catch (\Exception $e) {
            $response_obj->error = true;
            Log::debug($e->getMessage());
        } finally {
            return $response_obj;
        }
    }
}
