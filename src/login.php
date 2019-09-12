<?php
	require_once("includes/common.php");

	if (isset($_POST['userId']) and isset($_POST['password'])) {
		$userId = $_POST['userId'];
		$password = $_POST['password'];

		$userDAO = new UserDAO();
		
		if ($userDAO->authenticate($userId, $password)) {
			header("Location: home");
		}
		else {
			header("Location: .");
		}
	}
?>