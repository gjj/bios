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

	public function removeAllCourses() {
        $sql = 'TRUNCATE TABLE courses';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
	} 
	
	public function removeAllCompletedCourses() {
        $sql = 'TRUNCATE TABLE courses_completed';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
	} 
	
	public function removeAllPrerequisites() {
        $sql = 'TRUNCATE TABLE prerequisites';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
	} 

	public function addCourses($course) {
        $sql = 'INSERT INTO courses (course,school,title,description,exam_date,exam_start,exam_end) VALUES (:course, :school, :title, :description, :exam_date, :exam_start, :exam_end)';
        
        $connMgr = new ConnectionManager();       
        $db = $connMgr->getConnection();
         
        $query = $db->prepare($sql); 

        $query->bindParam(':course', $course->courseCode, PDO::PARAM_STR);
        $query->bindParam(':school', $course->school, PDO::PARAM_STR);
        $query->bindParam(':title', $course->title, PDO::PARAM_STR);
        $query->bindParam(':description', $course->description, PDO::PARAM_STR);
        $query->bindParam(':exam_date', $course->examDate, PDO::PARAM_STR);
        $query->bindParam(':exam_start', $course->examStart, PDO::PARAM_STR);
        $query->bindParam(':exam_end', $course->examEnd, PDO::PARAM_STR);


        $isAddOK = False;
        if ($query->execute()) {
            $isAddOK = True;
    ***REMOVED***

        return $isAddOK;
	}
	
	// Incomplete
	public function addCompletedCourses($userId, $course) {
        $sql = 'INSERT INTO courses_completed (user_id,course) VALUES (:userId, :course)';
        
        $connMgr = new ConnectionManager();       
        $db = $connMgr->getConnection();
         
        $query = $db->prepare($sql); 

		$query->bindParam(':userId', $userId, PDO::PARAM_STR);

        $isAddOK = False;
        if ($query->execute()) {
            $isAddOK = True;
    ***REMOVED***

        return $isAddOK;
	}

	public function addPrerequisites($course,$prerequisite) {
        $sql = 'INSERT INTO prerequisites (course,prerequisite) VALUES (:course, :prerequisite)';
        
        $connMgr = new ConnectionManager();       
        $db = $connMgr->getConnection();
         
        $query = $db->prepare($sql); 

		$query->bindParam(':course', $course, PDO::PARAM_STR);

        $isAddOK = False;
        if ($query->execute()) {
            $isAddOK = True;
    ***REMOVED***

        return $isAddOK;
	}




}