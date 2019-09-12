***REMOVED***

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
***REMOVED***
***REMOVED***The names of the attributes that should not be trimmed.
     *
***REMOVED***@var array
***REMOVED***
    protected $except = [
        'password',
        'password_confirmation',
    ];
}
