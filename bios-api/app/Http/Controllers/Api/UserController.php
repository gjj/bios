<?php

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
		$query = DB::table('students')
			->select(
			)
			->get();

		return response()->json([
			'data' => $query
		], 200);
	}
}