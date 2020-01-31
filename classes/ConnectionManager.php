***REMOVED***

class ConnectionManager {

	public function getConnection() {
		$host = "ip_here"; # deprovisioned
		$username = "root";
		$password = ""; # you can check the commit history for the password, but it doesn't work anymore! :)
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