***REMOVED***

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

***REMOVED***
***REMOVED***The attributes that are mass assignable.
     *
***REMOVED***@var array
***REMOVED***
    protected $fillable = [
        'name', 'email', 'password',
    ];

***REMOVED***
***REMOVED***The attributes that should be hidden for arrays.
     *
***REMOVED***@var array
***REMOVED***
    protected $hidden = [
        'password', 'remember_token',
    ];

***REMOVED***
***REMOVED***The attributes that should be cast to native types.
     *
***REMOVED***@var array
***REMOVED***
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
