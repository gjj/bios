***REMOVED***

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
***REMOVED***
***REMOVED***Run the migrations.
     *
***REMOVED***@return void
***REMOVED***
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
    ***REMOVED***);
***REMOVED***

***REMOVED***
***REMOVED***Reverse the migrations.
     *
***REMOVED***@return void
***REMOVED***
    public function down()
    {
        Schema::dropIfExists('users');
***REMOVED***
}
