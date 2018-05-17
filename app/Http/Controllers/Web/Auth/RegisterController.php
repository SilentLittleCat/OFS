<?php

namespace App\Http\Controllers\Web\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $messages = [
            'required' => ':attribute为必填项',
            'unique' => '手机号已注册'
        ];
        return Validator::make($data, [
            'tel' => 'required|unique:users',
            'password' => 'required'
        ], $messages);
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
            'tel' => $data['tel'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function showRegistrationForm(Request $request)
    {
        $recommend = null;
        if($request->has('id') && User::find($request->id) != null) {
            $recommend = $request->id;
        }
        $title = '用户注册';
        return view('web.auth.register', compact('recommend', 'title'));
    }

    protected function registered(Request $request, $user)
    {
        if($request->has('recommend') && $request->recommend != null && User::find($request->recommend) != null) {
            $user->recommend_by = $request->recommend;
        }
        $user->wechat_name = $user->tel;
        $user->save();
    }
}
