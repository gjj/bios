<?php

class RoundDAO {
    public function getCurrentRound() {
		$sql = "SELECT round, status FROM rounds";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
		$query->setFetchMode(PDO::FETCH_ASSOC);

		$query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
		
		// Returns my result set on success.
		return $result;
	}

	public function roundIsActive() {
		$sql = "SELECT round, status FROM rounds";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
		$query->setFetchMode(PDO::FETCH_ASSOC);

		$query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
		
		if ($result['status'] == "started") {
			return true;
		}
		else {
			return false;
		}
	}

	public function startRound($round = 1) {
		if ($round == 1) {
			$sql = "INSERT INTO rounds (round, status) VALUES (:round, 'started');";			
		}
		else {
			$sql = "UPDATE rounds SET round = :round, status = 'started'";
		}

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
		$query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':round', $round, PDO::PARAM_STR);
		$query->execute();
		
		// Returns my result set on success.
		if ($query->rowCount()) {
			return true;
		}
		else {
			return false;
		}
	}

	public function stopRound() {
		$sql = "UPDATE rounds SET status = 'stopped'";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
		$query->setFetchMode(PDO::FETCH_ASSOC);
		$query->execute();
		
		// Returns my result set on success.
		if ($query->rowCount()) {
			return true;
		}
		else {
			return false;
		}
	}

	public function removeAll() {
        $sql = 'TRUNCATE TABLE rounds';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    } 
}