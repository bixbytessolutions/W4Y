<?php

namespace App\Http\Controllers\Auth;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Session;
use App\company;

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
            'first_name' => 'required|string|max:255',
            'last_name' => 'string|max:255',
            'email' => 'required|string|email|max:255|unique:users_w4y',
            'password' => 'required|string|min:6|confirmed',
            'iscompany' => 'boolean',
            'company_name' =>'required_with:iscompany',
           
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

   
    if (isset($data['iscompany'])) {

       $iscompany=(int)$data['iscompany'];
       $companyid = company::create([
            'name' => $data['company_name'],
        ])->id;
       }

       else
       {
        $iscompany=0;
        $companyid=0;
       }

      return   User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'company' => $iscompany ,
            'companyid' => $companyid,
        ]);

    }

    protected function completeRegister($regtoken){
        $employeeData= user::where('reg_token','=',$regtoken)->first();
        if($employeeData){

        return view('auth.employeeuserregister',compact('employeeData'));
        }
         else
        {
            return redirect('/register');  
        }
    }

    protected function updateRegister(Request $request){
       
        $passworddata = $this->validate(request(),
        [
           'password' => ['required', 'min:6' , 'confirmed' , 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/']
            
       ], 
       [ 
           'password.required' => 'Password cannot be empty',
           'password.confirmed' => 'The password confirmation does not match.',
           'password.min' => 'The password length must have minimum 6 characters',
           'password.regex' => 'The password  must have at least one uppercase/lowercase letters, one number and one special characters',
           
       ]);

        $passworddata['password']=Hash::make($passworddata['password']);
        $passworddata['isactive']=true;
        $passworddata['reg_token']=NUll;
        
            if(User::where('reg_token','=',$request->reg_token)->update($passworddata))
            {
                Session::flash('message', "You Have successfully completed registration please login");
                return redirect()->route('login'); 
            }
           
    }

    protected function innviteUpdate($regtoken,$empid){
        $updateData['companyid']= decrypt($empid);
        $updateData['role_id']=15;
        $updateData['reg_token']=NUll;
        
            if(User::where('reg_token','=',$regtoken)->update($updateData))
            {
                Session::flash('message', "You Have successfully Added to Company");
                return redirect('/login'); 
            }
           
    }

    

     
}
