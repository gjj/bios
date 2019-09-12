***REMOVED***

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
***REMOVED***
***REMOVED***Get the path the user should be redirected to when they are not authenticated.
     *
***REMOVED***@param  \Illuminate\Http\Request  $request
***REMOVED***@return string
***REMOVED***
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
    ***REMOVED***
***REMOVED***
}
