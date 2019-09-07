***REMOVED***

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
***REMOVED***
***REMOVED***Handle an incoming request.
     *
***REMOVED***@param  \Illuminate\Http\Request  $request
***REMOVED***@param  \Closure  $next
***REMOVED***@param  string|null  $guard
***REMOVED***@return mixed
***REMOVED***
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect('/home');
    ***REMOVED***

        return $next($request);
***REMOVED***
}
