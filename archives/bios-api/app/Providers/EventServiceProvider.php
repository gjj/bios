***REMOVED***

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
***REMOVED***
***REMOVED***The event listener mappings for the application.
     *
***REMOVED***@var array
***REMOVED***
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

***REMOVED***
***REMOVED***Register any events for your application.
     *
***REMOVED***@return void
***REMOVED***
    public function boot()
    {
        parent::boot();

        //
***REMOVED***
}
