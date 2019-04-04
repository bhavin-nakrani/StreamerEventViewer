<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('twitch')
            ->setScopes(['user:read:email', 'channel:read:subscriptions', 'user:edit', 'user:read:broadcast'])
            ->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $twitchUser = Socialite::driver('twitch')->user();

        $now = \Carbon\Carbon::now();

        $user = User::firstOrNew([
            'email' => $twitchUser->email,
        ]);

        //$user = new User;
        $user->name = $twitchUser->name;
        //$user->email = $twitchUser->email;
        $user->password = bcrypt('admin@123');
        $user->created_at = $now;
        $user->updated_at = $now;
        $user->save();

        Auth::login($user);
        return redirect()->route('home');
    }
}
