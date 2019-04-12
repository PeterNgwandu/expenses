<?php

namespace App\Http\Controllers\Auth;

use Session;
use App\User;
use App\Company\Company;
use App\StaffLevel\StaffLevel;
use App\Department\Department;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'company_name' => 'required',
            'location' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $company = new Company();
        $company->company_name = $data['company_name'];
        $company->location = $data['location'];
        $company->description = $data['description'];
        $company->vat_status = "Registered";
        // $company->vat_percent = $data['vat_percent'];
        $company->save();

        Session::put('company_id', $company->id);

        $department = new Department();
        $department->name = "Computer Science";
        $department->company_id = Session::get('company_id');
        $department->save();

        $stafflevel = new StaffLevel();
        $stafflevel->name = "Head of Department (HOD)";
        $stafflevel->save();

        Session::put('department_id', $department->id);

        return User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'department_id' => Session::get('department_id'),
            'company_id' => Session::get('company_id'),
            'stafflevel_id' => $stafflevel->id,
        ]);
        return $user;
    }
}
