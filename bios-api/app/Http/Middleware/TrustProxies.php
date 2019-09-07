***REMOVED***

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
***REMOVED***
***REMOVED***The trusted proxies for this application.
     *
***REMOVED***@var array|string
***REMOVED***
    protected $proxies;

***REMOVED***
***REMOVED***The headers that should be used to detect proxies.
     *
***REMOVED***@var int
***REMOVED***
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
