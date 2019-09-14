<?php
	// this will autoload the class that we need in our code
	spl_autoload_register(function($class) {
		// we are assuming that it is in the same directory as common.php
		// otherwise we have to do
		// $path = 'path/to/' . $class . ".php"
		require_once __DIR__ . "/../classes/$class.php";
	});

	require_once __DIR__ . '/auth-api.php';

	// Begin session.
	session_start();

	// Check page.
	if (defined('BIOS_ADMIN')) {
		if (isLoggedIn() and currentUserRole() != 1) {
			header("Location: ../");
		}
	}

	function isLoggedIn() {
		if (isset($_SESSION['userid'])) {
			return true;
		}
		else {
			return false;
		}
	}

	function currentUser() {
		if (isset($_SESSION['userid']) and isset($_SESSION['role'])) {
			$userId = $_SESSION['userid'];
			$role = $_SESSION['role'];

			if ($role == 0) {
				$studentDAO = new StudentDAO();
				return $studentDAO->retrieveById($userId);
			}
		}
	}

	function currentUserRole() {
		if (isset($_SESSION['role'])) {
			return $_SESSION['role'];
		}
	}
	
	function printErrors() {
		if (isset($_SESSION['errors'])) {
			echo $_SESSION['errors'];
			
			/*foreach ($_SESSION['errors'] as $value) {
				echo "<li>" . $value . "</li>";
			}
			
			echo "</ul>";*/
			unset($_SESSION['errors']);
		}    
	}

	function addError($message) {
		$_SESSION['errors'] = $message;
	}

	function isMissingOrEmpty($name) {
		if (!isset($_REQUEST[$name])) {
			return "$name cannot be empty";
		}
	
		// client did send the value over
		$value = $_REQUEST[$name];
		if (empty($value)) {
			return "$name cannot be empty";
		}
	}

	// Added new helper function for JSON.
	function isMissingOrEmptyJson($name, $json) {
		if (!array_key_exists($name, $json)) {
			return "$name parameter not found";
		}
		
		$value = $json->$name;
		if (empty($value)) {
			return "$name cannot be empty";
		}
	}
	
	# check if an int input is an int and non-negative
	function isNonNegativeInt($var) {
		if (is_numeric($var) && $var >= 0 && $var == round($var))
			return true;
	}
	
	# check if a float input is is numeric and non-negative
	function isNonNegativeFloat($var) {
		if (is_numeric($var) && $var >= 0)
			return true;
	}
	
	# this is better than empty when use with array, empty($var) returns FALSE even when
	# $var has only empty cells
	function isEmpty($var) {
		if (isset($var) && is_array($var))
			foreach ($var as $key => $value) {
				if (empty($value)) {
				   unset($var[$key]);
				}
			}
	
		if (empty($var))
			return true;
	}