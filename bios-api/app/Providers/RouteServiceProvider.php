***REMOVED***

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
***REMOVED***
***REMOVED***This namespace is applied to your controller routes.
     *
***REMOVED***In addition, it is set as the URL generator's root namespace.
     *
***REMOVED***@var string
***REMOVED***
    protected $namespace = 'App\Http\Controllers';

***REMOVED***
***REMOVED***Define your route model bindings, pattern filters, etc.
     *
***REMOVED***@return void
***REMOVED***
    public function boot()
    {
        //

        parent::boot();
***REMOVED***

***REMOVED***
***REMOVED***Define the routes for the application.
     *
***REMOVED***@return void
***REMOVED***
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
***REMOVED***

***REMOVED***
***REMOVED***Define the "web" routes for the application.
     *
***REMOVED***These routes all receive session state, CSRF protection, etc.
     *
***REMOVED***@return void
***REMOVED***
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
***REMOVED***

***REMOVED***
***REMOVED***Define the "api" routes for the application.
     *
***REMOVED***These routes are typically stateless.
     *
***REMOVED***@return void
***REMOVED***
    protected function mapApiRoutes()
    {
        Route::prefix('app/json')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
***REMOVED***
}
