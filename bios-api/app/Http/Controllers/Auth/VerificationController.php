***REMOVED***

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

***REMOVED***
***REMOVED***Where to redirect users after verification.
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
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
***REMOVED***
}
