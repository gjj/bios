***REMOVED***

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BidController extends Controller {

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

	public function getBid(Request $request) {
		$messages = array();

		// TODO: include token auth.
		if ($request->query('r')) {
			$query = DB::table('bids')
				->select(
					'id AS row',
					'user_id AS userid',
					DB::raw('ROUND(amount, 1) AS amount'),
					'result'
				);

			$r = json_decode($request->query('r'));

			if (isset($r->course) and isset($r->section)) {
				$query2 = DB::table('courses')
					->where('course', '=', $r->course)
					->get();

				if (count($query2)) {
					$query3 = DB::table('sections')
						->where('course', '=', $r->course)
						->where('section', '=', $r->section)
						->get();

					if (count($query3)) {
						$query = $query->where('code', '=', $r->course)
							->where('section', '=', $r->section)
							->where('result', '!=', 'dropped')
							->where('round', '=', $this->activeOrLatestRound())
							->get();

						if (count($query)) {
							return response()->json([
								'status' => 'success',
								'students' => $query
							], 200)
							->setEncodingOptions(JSON_PRESERVE_ZERO_FRACTION);
						}
						else {
							array_push($messages, "No results found with the given course code and section.");
						}
					}
					else {
						array_push($messages, "No such section ID exists for the particular course.");

					}
					
				}
				else {
					array_push($messages, "Invalid course code.");

				}
				
			}
			else {
				array_push($messages, "Missing JSON fields! Both course code and section are required.");
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

	public function updateBid(Request $request) {
		$messages = array();

		// TODO: include token auth.
		if ($request->query('r')) {
			$r = json_decode($request->query('r'));

			if (isset($r->course) and isset($r->section)) {
				$course = DB::table('courses')
					->where('course', '=', $r->course)
					->get();

				if (count($course)) {
					$section = DB::table('sections')
						->where('course', '=', $r->course)
						->where('section', '=', $r->section)
						->get();

					if (count($section)) {
						$user = DB::table('users')
							->where('user_id', '=', $r->userid)
							->get();

						if (count($user)) {
							$query = DB::table('bids')
								->update(
									['amount' => $r->amount]
								)
								->where('user_id', '=', $r->userid)
								->where('code', '=', $r->course)
								->where('section', '=', $r->section)
								#->where('result', '!=', 'dropped')
								#->where('round', '=', $this->activeOrLatestRound())
								->get();

							if (count($query)) {
								return response()->json([
									'status' => 'success',
									'students' => $query
								], 200)
								->setEncodingOptions(JSON_PRESERVE_ZERO_FRACTION);
							}
							else {
								array_push($messages, "No results found with the given course code and section.");
							}
						}
						else {
							array_push($messages, "No user found with that user ID.");
						}
					}
					else {
						array_push($messages, "No such section ID exists for the particular course.");

					}
					
				}
				else {
					array_push($messages, "Invalid course code.");

				}
				
			}
			else {
				array_push($messages, "Missing JSON fields! Both course code and section are required.");
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

	public function deleteBid(Request $request) {

	}

	private function activeOrLatestRound() {
		$query = DB::table('rounds')
			->select(
				'round',
				'start'
			)
			->orderBy('start'); // Descending.

		return $query->round;
	}
}