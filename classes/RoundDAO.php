***REMOVED***

class RoundDAO {
    public function getCurrentRound() {
		$sql = "SELECT round, status FROM rounds WHERE status != 'ended'";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
		$query->setFetchMode(PDO::FETCH_ASSOC);

		$query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
		
		// Returns my result set on success.
		return $result;
	}
}