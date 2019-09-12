***REMOVED***

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
***REMOVED***
***REMOVED***The policy mappings for the application.
     *
***REMOVED***@var array
***REMOVED***
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

***REMOVED***
***REMOVED***Register any authentication / authorization services.
     *
***REMOVED***@return void
***REMOVED***
    public function boot()
    {
        $this->registerPolicies();

        //
***REMOVED***
}
