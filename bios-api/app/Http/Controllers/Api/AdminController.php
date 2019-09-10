***REMOVED***

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminController extends Controller {

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

	public function authenticate(Request $request) {

		$login = DB::table('users')
			->where('user_id', '=', $request->input('userId'))
			->where('password', '=', $request->input('password'))
			->where('role', '=', '1')
			->get();
		if (count($login)) {
			return response()->json([
				'status' => 'success',
				'token' => 'X'
			], 200);
		}
		else {
			return response()->json([
				'status' => 'failure',
				'message' => 'Invalid username or password.'
			], 200);
		}
	}

	public function bootstrap(Request $request) {
		// Not implemented.
	}

	public function getDump(Request $request) {
		$messages = array();

		// TODO: include token auth.
		$courses = DB::table('courses')
			->select(
				'course',
				'school',
				'title',
				'description',
				DB::raw('DATE_FORMAT(exam_date, "%Y%m%d") AS "exam date"'),
				DB::raw('TIME_FORMAT(exam_start, "%l%i") AS "exam start"'),
				DB::raw('TIME_FORMAT(exam_end, "%l%i") AS "exam end"')
			)
			->orderBy('course')
			->get();

		$sections = DB::table('sections')
			->select(
				'course',
				'section',
				'day',
				DB::raw('TIME_FORMAT(start, "%l%i") AS "start"'),
				DB::raw('TIME_FORMAT(end, "%l%i") AS "end"'),
				'instructor',
				'venue',
				'size'
			)
			->orderBy('course')
			->orderBy('section')
			->get();
		
		$students = DB::table('students')
			->select(
				'user_id AS userid',
				'password',
				'name',
				'school',
				'edollar'
			)
			->orderBy('userid')
			->get();

		$prerequisites = DB::table('prerequisites')
			->select(
				'course',
				'prerequisite'
			)
			->get();

		$bids = DB::table('bids')
			->select(
				'user_id AS userid',
				'amount',
				'code AS course', // Check with prof.
				'section'
			)
			->get();

		$completedCourse = DB::table('course_completed')
			->select(
				'user_id AS userid',
				'code AS course' // Check with prof.
			)
			->get();

		$sectionStudents = DB::table('bids')
			->select(
				'user_id AS userid',
				'code AS course', // Check with prof.
				'section',
				'amount'
			)
			->where('result', '=', 'IN')
			->get();

		return response()->json([
			'status' => 'success',
			'course' => $courses,
			'section' => $sections,
			'student' => $students,
			'prerequisite' => $prerequisites,
			'completed-course' => $completedCourse,
			'section-student' => $sectionStudents
		], 200);
	}
}