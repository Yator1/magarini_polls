<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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
        return Validator::make($data, [
                
            'name' => ['required', 'string', 'max:255'],
            'id_no' => ['required', 'integer', 'unique:users'],
            'phone_no' => ['required', 'integer', 'unique:users'],
            'role_id' => ['required', 'integer'],
            'sub_county_id' => ['required', 'integer'],
            'ward_id' => ['required', 'integer'],
            'polling_station_id' => ['required', 'integer'],
            'h_education_level_id' => ['required', 'integer'],
            'guardian_name' => ['required', 'string'],
            'guardian_id_no' => ['required', 'integer'],
            'guardian_phone_no' => ['required', 'integer'],
            'gender' => ['required', 'string'],
            'guardian_relationship_id' => ['required', 'integer'],
            'guardian_email' => ['nullable', 'email', 'unique:users'],
            'course_prefered' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],

        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // return request()->file('avatar');
        if (request()->has('avatar')) {
            $avatar = request()->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = public_path('/images/');
            $avatar->move($avatarPath, $avatarName);
        }

        return User::create([

            'name' => $data['name'],
            'id_no' => $data['id_no'],
            'phone_no' => $data['phone_no'],
            'role_id' => $data['role_id'],
            'sub_county_id' => $data['sub_county_id'],
            'ward_id' => $data['ward_id'],
            'polling_station_id' => $data['polling_station_id'],
            'h_education_level_id' => $data['h_education_level_id'],
            'guardian_name' => $data['guardian_name'],
            'guardian_id_no' => $data['guardian_id_no'],
            'guardian_phone_no' => $data['guardian_phone_no'],
            'gender' => $data['gender'],
            'guardian_relationship_id' => $data['guardian_relationship_id'],
            'guardian_email' => $data['guardian_email'],
            'course_prefered' => $data['course_prefered'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'avatar' => $avatarName,
            'status' => true, 
        ]);
    }
}
