***REMOVED***

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFailedJobsTable extends Migration
{
***REMOVED***
***REMOVED***Run the migrations.
     *
***REMOVED***@return void
***REMOVED***
    public function up()
    {
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
    ***REMOVED***);
***REMOVED***

***REMOVED***
***REMOVED***Reverse the migrations.
     *
***REMOVED***@return void
***REMOVED***
    public function down()
    {
        Schema::dropIfExists('failed_jobs');
***REMOVED***
}
