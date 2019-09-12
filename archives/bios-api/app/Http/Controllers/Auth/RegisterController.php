***REMOVED***

namespace App\Http\Controllers\Auth;

use App\User;
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

***REMOVED***
***REMOVED***Where to redirect users after registration.
     *
***REMOVED***@var string
***REMOVED***
    protected $redirectTo = '/home';

***REMOVED***
***REMOVED***Create a new controller instance.
     *
***REMOVED***@return void
***REMOVED***
    public function __construct()
    {
        $this->middleware('guest');
***REMOVED***

***REMOVED***
***REMOVED***Get a validator for an incoming registration request.
     *
***REMOVED***@param  array  $data
***REMOVED***@return \Illuminate\Contracts\Validation\Validator
***REMOVED***
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
***REMOVED***

***REMOVED***
***REMOVED***Create a new user instance after a valid registration.
     *
***REMOVED***@param  array  $data
***REMOVED***@return \App\User
***REMOVED***
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
***REMOVED***
}
