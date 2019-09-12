***REMOVED***

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
***REMOVED***
***REMOVED***A list of the exception types that are not reported.
     *
***REMOVED***@var array
***REMOVED***
    protected $dontReport = [
        //
    ];

***REMOVED***
***REMOVED***A list of the inputs that are never flashed for validation exceptions.
     *
***REMOVED***@var array
***REMOVED***
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

***REMOVED***
***REMOVED***Report or log an exception.
     *
***REMOVED***@param  \Exception  $exception
***REMOVED***@return void
***REMOVED***
    public function report(Exception $exception)
    {
        parent::report($exception);
***REMOVED***

***REMOVED***
***REMOVED***Render an exception into an HTTP response.
     *
***REMOVED***@param  \Illuminate\Http\Request  $request
***REMOVED***@param  \Exception  $exception
***REMOVED***@return \Illuminate\Http\Response
***REMOVED***
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
***REMOVED***
}
