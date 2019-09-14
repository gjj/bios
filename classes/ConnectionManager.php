<?php

class ConnectionManager {

	public function getConnection() {
		$host = "pixely.southeastasia.cloudapp.azure.com";
		$username = "sup";
		$password = "Letsgetit@spm$1";
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