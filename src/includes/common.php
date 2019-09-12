<?php
	// this will autoload the class that we need in our code
	spl_autoload_register(function($class) {
		// we are assuming that it is in the same directory as common.php
		// otherwise we have to do
		// $path = 'path/to/' . $class . ".php"
		require_once "classes/$class.php";
	});

	// Begin session.
	session_start();
	
	