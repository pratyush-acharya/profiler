<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\User;
use Exception;
use Laravel\Socialite\Facades\Socialite;
use Hash;
use App\Models\LoginDetail;

use Request;
use Illuminate\Support\Facades\Auth;


class UserLogin extends Component
{
    protected $listeners = ['login' => 'login'];

    public function render()
    {
        return view('livewire.user-login');
    }
    // $clientIP = Request::getClientIp(true);
    // LoginDetail::create([
    //     'ip' => $clientIP,
    //     'user_id' => Auth::user()->id,
    // ]);
    // session()->flash('success', "You are Logged In successfully.");
    // return redirect('/'.Auth::user()->role);

    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
        try {

            $user = Socialite::driver('google')->user();
            // first check if the user with google_id exists
            $findUser = User::where('email', $user->email)->first();

            if ($findUser) {

                Auth::login($findUser);
                session()->flash('loginSuccess', "You are Logged In successfully.");

                return redirect('/' . Auth::user()->role);
            }
            session()->flash('danger', "No Active User Found For This Email");
            return redirect()->route('login');

        } catch (Exception $e) {
            dd($e);
        }
    }
    public function login($id_token)
    {
        $CLIENT_ID = '848005554800-hq4fu22o449tu1ff2g9tsgdbaksljs9c.apps.googleusercontent.com';
        $client = new \Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend

        // $id_token = 'a'.substr($id_token, 1);
        // $id_token = 'abc';

        $token_array = explode('.', $id_token);

        if (count($token_array) == 3) {
            try {
                $payload = $client->verifyIdToken($id_token);

                if ($payload && !isset($payload['error'])) {
                    $validISS = array('accounts.google.com', 'https://accounts.google.com');
                    $validAUD = $CLIENT_ID;

                    if (in_array($payload['iss'], $validISS)) {
                        if ($payload['aud'] == $validAUD) {
                            if (isset($payload['hd']))
                                $domain = $payload['hd'];
                            else
                                $domain = NULL;
                            if ($domain == 'deerwalk.edu.np') {
                                $isVerified = $payload['email_verified'];

                                if ($isVerified) {
                                    $userid = $payload['sub'];
                                    $email = $payload['email'];
                                    $exp = $payload['exp'];

                                    $isUserExists = User::where('email', $email)->where('status', 1)->count();
                                    if ($isUserExists > 0) {
                                        $authUser = User::where('email', $email)->where('status', 1)->first();
                                        Auth::login($authUser);

                                        session()->flash('loginSuccess', "You are Logged In successfully.");
                                        return redirect('/' . Auth::user()->role);
                                    } else {
                                        session()->flash('danger', "No Active User Found For This Email");
                                    }
                                } else {
                                    session()->flash('danger', "Login Email Has Not Been Verified");
                                }
                            } else {
                                session()->flash('danger', "Invalid  Email Provider");
                            }
                        } else {
                            session()->flash('danger', "Invalid Application Token");
                        }
                    } else {
                        session()->flash('danger', "Invalid Token Generator");
                    }
                } else {
                    session()->flash('danger', "Invalid ID Token");
                }
            } catch (Exception $e) {
                session()->flash('danger', "Error occured while processing request");
            }
        } else {
            dd("Invalid Id Token sent");
        }
    }
}
