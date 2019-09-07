***REMOVED***

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
***REMOVED***
***REMOVED***Bootstrap any application services.
     *
***REMOVED***@return void
***REMOVED***
    public function boot()
    {
        Broadcast::routes();

        require base_path('routes/channels.php');
***REMOVED***
}
