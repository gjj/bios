<?php

class StudentDAO {
    public function retrieveAll() {
		$sql = "SELECT students.user_id AS userid, password, name, school, FORMAT(edollar, 1) AS edollar FROM users, students WHERE users.role = 0 AND users.user_id = students.user_id";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
		$query->setFetchMode(PDO::FETCH_ASSOC);

		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		// Returns my result set on success.
		return $result;
	}
    
    public function retrieveById($userId) {
		$sql = "SELECT students.user_id AS userid, password, name, school, FORMAT(edollar, 1) AS edollar FROM users, students WHERE users.role = 0 AND users.user_id = students.user_id AND users.user_id = :userId";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);

		$query->execute();
		//$query->setFetchMode(PDO::FETCH_CLASS, "Student");
		$result = $query->fetch(PDO::FETCH_ASSOC);
		
		// Returns my result set on success.
		return $result;
	}
}