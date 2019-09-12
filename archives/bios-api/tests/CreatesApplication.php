***REMOVED***

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
***REMOVED***
***REMOVED***Creates the application.
     *
***REMOVED***@return \Illuminate\Foundation\Application
***REMOVED***
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
***REMOVED***
}
