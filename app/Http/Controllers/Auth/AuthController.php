<?php

namespace App\Http\Controllers\Auth;

use App\Managers\ReferralKeyManager;
use App\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers {
        showRegistrationForm as traitShowRegistrationForm;
    }
    use ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /** @var ReferralKeyManager */
    protected $referralKeyManager;

    /**
     * Create a new authentication controller instance.
     *
     * @param ReferralKeyManager $referralKeyManager
     */
    public function __construct(ReferralKeyManager $referralKeyManager)
    {
        $this->referralKeyManager = $referralKeyManager;

        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'password'      => bcrypt($data['password']),
            'referral_id'   => $this->referralKeyManager->findUserIdByReferralKey($data['referralKey']),
            'referral_key'  => $this->referralKeyManager->generate(),
        ]);
    }

    /**
     * Extend showRegistrationForm from AuthenticatesAndRegistersUsers trait
     *
     * @param string $referralKey
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm($referralKey = '')
    {
        if ($referralKey && !$this->referralKeyManager->check($referralKey)) {
            throw new NotFoundHttpException('Referral ID not found');
        }

        return $this->traitShowRegistrationForm()->with('referralKey', $referralKey);
    }
}
