***REMOVED***

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

***REMOVED***
***REMOVED***Where to redirect users after resetting their password.
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
}
