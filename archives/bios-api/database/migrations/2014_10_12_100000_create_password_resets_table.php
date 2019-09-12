***REMOVED***

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePasswordResetsTable extends Migration
{
***REMOVED***
***REMOVED***Run the migrations.
     *
***REMOVED***@return void
***REMOVED***
    public function up()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
    ***REMOVED***);
***REMOVED***

***REMOVED***
***REMOVED***Reverse the migrations.
     *
***REMOVED***@return void
***REMOVED***
    public function down()
    {
        Schema::dropIfExists('password_resets');
***REMOVED***
}
