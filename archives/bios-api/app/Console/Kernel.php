***REMOVED***

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
***REMOVED***
***REMOVED***The Artisan commands provided by your application.
     *
***REMOVED***@var array
***REMOVED***
    protected $commands = [
        //
    ];

***REMOVED***
***REMOVED***Define the application's command schedule.
     *
***REMOVED***@param  \Illuminate\Console\Scheduling\Schedule  $schedule
***REMOVED***@return void
***REMOVED***
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
***REMOVED***

***REMOVED***
***REMOVED***Register the commands for the application.
     *
***REMOVED***@return void
***REMOVED***
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
***REMOVED***
}
