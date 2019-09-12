<?php

class UserDAO {
	public function login($username, $password) {
		$sql = "SELECT user_id AS userid, password, role FROM users WHERE user_id = :userId AND password = :password";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
		$query->setFetchMode(PDO::FETCH_ASSOC);
		$query->bindParam(':userId', $username, PDO::PARAM_STR);
		$query->bindParam(':password', $password, PDO::PARAM_STR);

		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
		
		// Returns my result set i.e. userid, password, role (see SQL statement above) on success.
		return $result;
	}

	
}