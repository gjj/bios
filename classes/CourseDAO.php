***REMOVED***

class CourseDAO {
    public function retrieveAll() {
		$sql = "SELECT course, school, title, description, DATE_FORMAT(exam_date, '%Y%m%d') AS 'exam date', TIME_FORMAT(exam_start, '%k%i') AS 'exam start', TIME_FORMAT(exam_end, '%k%i') AS 'exam end' FROM courses";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
		$query->setFetchMode(PDO::FETCH_ASSOC);

		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		// Returns my result set on success.
		return $result;
	}
    
    public function retrieveByCode($courseCode) {
		$sql = "SELECT course, school, title, description, DATE_FORMAT(exam_date, '%Y%m%d') AS 'exam date', TIME_FORMAT(exam_start, '%k%i') AS 'exam start', TIME_FORMAT(exam_end, '%k%i') AS 'exam end' FROM courses WHERE courses.course = :courseCode";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);

		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
		
		// Returns my result set on success.
		return $result;
	}

	public function sectionsCount($courseCode) {
		$sql = "SELECT COUNT(section) AS 'sections_offered' FROM sections WHERE course = :courseCode";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);

		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
		
		// Returns my result set on success.
		return $result;
	}

	public function searchPrerequisites($courseCode) {
		$sql = "SELECT prerequisite FROM prerequisites WHERE course = :courseCode";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);

		$query->execute();

		$result = array();

		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push($result, $row['prerequisite']);
		}
		
		// Returns my result set on success.
		return $result;
	}

	public function searchPrerequisitesOf($courseCode) {
		$sql = "SELECT course FROM prerequisites WHERE prerequisite = :courseCode";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);

		$query->execute();

		$result = array();

		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push($result, $row['course']);
		}
		
		// Returns my result set on success.
		return $result;
	}
}