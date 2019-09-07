***REMOVED***

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

***REMOVED***
***REMOVED***Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
***REMOVED***
}
