***REMOVED***

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
***REMOVED***
***REMOVED***Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
***REMOVED***@var bool
***REMOVED***
    protected $addHttpCookie = true;

***REMOVED***
***REMOVED***The URIs that should be excluded from CSRF verification.
     *
***REMOVED***@var array
***REMOVED***
    protected $except = [
        //
    ];
}
