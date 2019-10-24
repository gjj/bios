<?php

class ConnectionManager {

	public function getConnection() {
		/*
		$host = "18.136.126.161";
		$username = "root";
		$password = "worzr3v50e8Z";
		$databaseName = "bios";
		$port = 3306;
		*/
		$host = "localhost";
		$username = "root";
		$password = "";
		$databaseName = "bios";
		$port = 3306;

		$connectionString = "mysql:host={$host};dbname={$databaseName};port={$port}";

		$connection = new PDO($connectionString, $username, $password, array(
			PDO::ATTR_EMULATE_PREPARES => false
		));
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $connection;
	}
}