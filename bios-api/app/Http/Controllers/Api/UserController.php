***REMOVED***

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function index() {
		$this->middleware('guest');
	}

	public function show() {
		$this->middleware('guest');
	}

	public function getUser(Request $request) {
		$messages = array();
		
		// TODO: include token auth.
		if ($request->query('r')) {
			$query = DB::table('students')
				->select(
					'user_id AS userid',
					'password',
					'name',
					'school',
					'edollar'
				);
				//->first();

			
			$r = json_decode($request->query('r'));

			if (isset($r->userid)) {
				$query = $query->where('user_id', '=', $r->userid)
					->first();
				if ($query) {
					return response()->json([
						'status' => 'success',
						'userid' => $query->userid,
						'password' => $query->password,
						'name' => $query->name,
						'school' => $query->school,
						'edollar' => $query->edollar
					], 200);
				}
				else {
					array_push($messages, "Invalid user ID.");
				}
			}
			else {
				array_push($messages, "Unable to find user ID field in request.");
			}
		}
		else {
			array_push($messages, "Missing request parameter.");
		}
		return response()->json([
			'status' => 'error',
			'message' => $messages
			], 200);
	}
}