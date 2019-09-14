<?php
	require_once 'includes/common.php';

	// Check if form has posted the userId and password fields i.e. not empty.
	if (isset($_POST['userId']) and isset($_POST['password'])) {
		$userId = $_POST['userId'];
		$password = $_POST['password'];

		// Instantiate my User Data Access Object.
		$userDAO = new UserDAO();

		// Since we know that login(userId, pw) returns the record on success, or nothing on failure, we assign a variable to hold the results.
		$login = $userDAO->login($userId, $password);
		print_r($login);
		
		// If it's not empty...
		if ($login) {
			// I should be able to access the SQL results - we know that it returns as (userid, password, role)
			$username = $login['userid'];
			$password = $login['password'];
			$role = $login['role'];

			// Keep in session.
			$_SESSION['userid'] = $username;
			$_SESSION['role'] = $role;

			// Then we check for authentication. As discussed in our meeting, role 0 - students, role 1 - admin.
			if ($role == 1) {
				header("Location: admin/home");				
			}
			else {
				header("Location: home");
			}
		}
		else {
			addError("Invalid username or password.");
			header("Location: .");
		}
	}
	else {
		addError("Invalid username or password.");
		header("Location: .");
	}
?>