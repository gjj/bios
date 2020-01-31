<?php
	require_once 'includes/common.php';

	// Clear current session.
	session_destroy();

	// Redirect back to home.
	header("Location: .");