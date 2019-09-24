***REMOVED***

class BidDAO {
    /*
       Bid statuses:
       Bid statuses in cart = {cart, submitted}
       On round start status = {-} but only use submitted for processing
       After round proc status = {in, out}
    */

***REMOVED***
***REMOVED***This methods returns all BIDS by user.
***REMOVED***@params $userId The user ID to search for.
***REMOVED***@params $round The current round.
***REMOVED***@return All BIDS that matches the search params.
***REMOVED***
    public function retrieveAllBidsByUser($userId, $round) {
		$sql = "SELECT user_id, amount, bids.course, bids.section, result, round, courses.school, courses.title, sections.day, sections.start, sections.end, sections.instructor, sections.venue, sections.size FROM bids, courses, sections WHERE bids.course = courses.course AND bids.course = sections.course AND bids.section = sections.section AND user_id = :userId AND round = :round AND result = 'submitted'";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $result = $this->updateDayOfWeek($result);
		
		// Returns my result set on success.
		return $result;
***REMOVED***

***REMOVED***
***REMOVED***This methods returns all cart items by user of a given round
***REMOVED***@params $userId The user ID to search for.
***REMOVED***@params $round The current round.
***REMOVED***@return All cart items that matches the search params.
***REMOVED***
    public function retrieveAllCartItemsByUser($userId, $round) {
		$sql = "SELECT user_id, amount, bids.course, bids.section, result, round, courses.school, courses.title, sections.day, sections.start, sections.end, sections.instructor, sections.venue, sections.size FROM bids, courses, sections WHERE bids.course = courses.course AND bids.course = sections.course AND bids.section = sections.section AND user_id = :userId AND round = :round AND result = 'cart'";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $result = $this->updateDayOfWeek($result);
		
		// Returns my result set on success.
		return $result;
***REMOVED***

***REMOVED***
***REMOVED***This methods returns all cart items by a user given the code and section.
***REMOVED***@params $userId The user ID to search for.
***REMOVED***@params $coursecode The course code.
***REMOVED***@params $section The section.
***REMOVED***@params $round The current round.
***REMOVED***@return All cart items that matches the search params.
***REMOVED***
    public function retrieveCartItemsByCodeAndSection($userId, $courseCode, $section, $round) {
		$sql = "SELECT user_id, amount, bids.course, bids.section, result, round, courses.school, courses.title, sections.day, sections.start, sections.end, sections.instructor, sections.venue, sections.size FROM bids, courses, sections WHERE bids.course = courses.course AND bids.course = sections.course AND bids.section = sections.section AND user_id = :userId AND bids.course = :courseCode AND bids.section = :section AND round = :round AND result = 'cart'";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        //$result = $this->updateDayOfWeek($result);
		
		// Returns my result set on success.
		return $result;
***REMOVED***

***REMOVED***
***REMOVED***Check if the course and section pair has already been added to cart.
***REMOVED***@params $userId The user ID to search for.
***REMOVED***@params $coursecode The course code.
***REMOVED***@params $section The section.
***REMOVED***@params $round The current round.
***REMOVED***@return true if already added to cart, false if not in the cart.
***REMOVED***
    public function checkIfAddedToCart($userId, $courseCode, $section, $round) {
		$sql = "SELECT * FROM bids WHERE user_id = :userId AND course = :courseCode AND section = :section AND ((round = :round AND (result = 'cart' OR result = 'submitted')) OR result = 'in')";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($result)) {
            return true;
    ***REMOVED***

		return false;
***REMOVED***

***REMOVED***
***REMOVED***Delete cart item.
***REMOVED***@params $userId The user ID to search for.
***REMOVED***@params $coursecode The course code.
***REMOVED***@params $section The section.
***REMOVED***@params $round The current round.
***REMOVED***@return true if successfully deleted, false if not.
***REMOVED***
    public function deleteCartItemByCodeAndSection($userId, $courseCode, $section, $round) {
		$sql = "DELETE FROM bids WHERE course = :courseCode AND section = :section AND user_id = :userId AND round = :round AND result = 'cart'";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        
		$isDeleteOK = false;

        if ($query->execute()) {
            $isDeleteOK = true;
    ***REMOVED***

        return $isDeleteOK;
***REMOVED***

    /*
***REMOVED***Check if bidded course is same as user's current school, only used in Round 1.
***REMOVED***"not own school course"	This only happens in round 1 where students are allowed to bid for modules from their own school.
***REMOVED***bid.csv validation 1/7
***REMOVED***
    public function checkOwnSchoolCourse($userSchool, $courseCode) {
        $sql = "SELECT course, school FROM courses WHERE course = :courseCode";
        
		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($result['school'] == $userSchool) {
            return true;
    ***REMOVED***

        return false;
***REMOVED***

    /*
***REMOVED***Check for existing cart bids (passed as $coursesections) WITH existing records (i.e. got into the course/submitted bids) for timetable conflicts.
***REMOVED***"class timetable clash"	The class timeslot for the section clashes with that of a previously bidded section.
***REMOVED***bid.csv validation 2/7
***REMOVED***
    public function checkTimetableConflicts($userId, $courseSections, $round) {
        $inClauseBuilder = "";
        // SANITISE INPUTS PLSSSS!! this is vulnerable to SQL injection.
        for ($i = 0; $i < count($courseSections); $i++) {
            $inClauseBuilder .= "('{$courseSections[$i]['course']}', '{$courseSections[$i]['section']}')";

            if ($i != (count($courseSections)-1)) {
                $inClauseBuilder .= ", ";
        ***REMOVED***
    ***REMOVED***

        $sql = "SELECT course, day, start, end FROM sections WHERE (course, section) IN (" . $inClauseBuilder . ") ";
        $sql .= "UNION SELECT course, day, start, end FROM sections WHERE (course, section) IN (SELECT course, section FROM bids WHERE user_id = :userId AND ((result = 'submitted' AND round = :round) OR result = '-' OR result = 'in')) ";
        $sql .= "ORDER BY day, start";
        
        // sort by Day, then search
		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        
        // Taking advantage that my result set is sorted by day, start ASC. Only need one passthrough and hence O(n)
        for ($i = 0; $i < count($result); $i++) {
            // Prevent index out of bounds exception.
            if (($i + 1) < count($result)) {
                $timeslot1 = $result[$i];
                $timeslot2 = $result[$i + 1];

                // Only makes sense for me to check if both timeslot's day is the same. In other words, Monday 3pm lesson and Tuesday 3pm lesson = no clash!
                if ($timeslot1['day'] == $timeslot2['day']) {
                    if ($timeslot1['end'] >= $timeslot2['start']) {
                        return true;
                ***REMOVED***
            ***REMOVED***
        ***REMOVED***
    ***REMOVED***

		return false;
***REMOVED***

    /*
***REMOVED***Check for existing cart bids (passed as $courseSections) WITH existing records (i.e. got into the course/submitted bids) for exam conflicts.
***REMOVED***"exam timetable clash"	The exam timeslot for this section clashes with that of a previously bidded section.
***REMOVED***bid.csv validation 3/7
***REMOVED***
    public function checkExamConflicts($userId, $courseSections, $round) {
        $courses = array();
        for ($i = 0; $i < count($courseSections); $i++) {
            array_push($courses, $courseSections[$i]['course']);
    ***REMOVED***

        $sql = "SELECT course, exam_date, exam_start, exam_end FROM courses WHERE course IN (" . implode(",", $courses) . ") ";
        $sql .= "UNION SELECT course, exam_date, exam_start, exam_end FROM courses WHERE course IN (SELECT course FROM bids WHERE user_id = :userId AND ((result = 'submitted' AND round = :round) OR result = '-' OR result = 'in')) ";
        $sql .= "ORDER BY day, start";
        
        // sort by Day, then search
		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        
        // Taking advantage that my result set is sorted by day, start ASC. Only need one passthrough and hence O(n)
        for ($i = 0; $i < count($result); $i++) {
            // Prevent index out of bounds exception.
            if (($i + 1) < count($result)) {
                $timeslot1 = $result[$i];
                $timeslot2 = $result[$i + 1];

                // Only makes sense for me to check if both timeslot's day is the same. In other words, Monday 3pm lesson and Tuesday 3pm lesson = no clash!
                if ($timeslot1['day'] == $timeslot2['day']) {
                    if ($timeslot1['end'] >= $timeslot2['start']) {
                        return true;
                ***REMOVED***
            ***REMOVED***
        ***REMOVED***
    ***REMOVED***

		return false;
***REMOVED***

    /*
***REMOVED***Check if user has completed the prerequisites.
***REMOVED***"incomplete prerequisites"	student has not completed the prerequisites for this course.
***REMOVED***bid.csv validation 4/7
***REMOVED***
    public function hasPrerequisites($courseCode) {
		$sql = "SELECT prerequisite FROM prerequisites WHERE course = :courseCode;";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($result)) {
            return true;
    ***REMOVED***

		return false;
***REMOVED***

    /* Only use when you know that the course has prerequisites. */
    public function hasCompletedPrerequisites($userId, $courseCode) {
		$sql = "SELECT * FROM courses_completed WHERE user_id = :userId AND course IN (SELECT prerequisite FROM prerequisites WHERE course = :courseCode)";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($result)) {
            return true;
    ***REMOVED***

		return false;
***REMOVED***

    /*
***REMOVED***Check if course has already been completed. Why do you want to take the same course again, right?
***REMOVED***"course completed"	student has already completed this course.
***REMOVED***bid.csv validation 5/7
***REMOVED***
    public function hasCompletedCourse($userId, $courseCode) {
		$sql = "SELECT * FROM courses_completed WHERE user_id = :userId AND course = :courseCode";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($result)) {
            return true;
    ***REMOVED***

		return false;
***REMOVED***

    /*
***REMOVED***Return count(confirmed mods + submitted bids).
***REMOVED***"section limit reached"	student has already bidded for 5 sections.
***REMOVED***bid.csv validation 6/7
***REMOVED***
    public function countBids($userId, $round) {
		$sql = "SELECT * FROM bids WHERE user_id = :userId AND (((result = 'submitted' OR result = '-') AND round = :round) OR result = 'in')";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		// Returns my result set on success.
		return $result;
***REMOVED***

    /*
***REMOVED***Get user's edollar.
***REMOVED***"not enough e-dollar"	student has not enough e-dollars to place the bid. If it is an update of a previous bid for the same course, account for the e$ gained back from the cancellation
***REMOVED***bid.csv validation 7/7
***REMOVED***
    public function getEDollar($userId) {
		$sql = "SELECT edollar FROM users WHERE user_id = :userId";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
		
		// Returns my result set on success.
		return $result;
***REMOVED***

    public function addToCart($userId, $courseCode, $section, $round) {
        $sql = "INSERT INTO bids (user_id, course, section, result, round) VALUES (:userId, :courseCode, :section, 'cart', :round)";
        
        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();
         
        $stmt = $conn->prepare($sql); 

        $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
        $stmt->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->bindParam(':round', $round, PDO::PARAM_STR);
        
        $isAddOK = false;

        if ($stmt->execute()) {
            $isAddOK = true;
    ***REMOVED***

        return $isAddOK;
***REMOVED***

    public function addBid($userId, $courseCode, $section, $amount, $round) {
		$sql = "UPDATE bids SET result = 'submitted', amount = :amount WHERE user_id = :userId AND course = :courseCode AND section = :section AND result = 'cart' AND round = :round";
        
        // Note: It's update and not add because we combine into one table.
        //$sql = 'UPDATE user SET gender=:gender, password=:password, name=:name WHERE username=:username';

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':amount', $amount, PDO::PARAM_STR);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);
		
		$isUpdateOk = False;
        if ($query->execute()) {
            $isUpdateOk = True;
    ***REMOVED***

        return $isUpdateOk;
***REMOVED***

    public function updateBid($userId, $courseCode, $section, $amount, $round) {
		$sql = "UPDATE bids SET amount = :amount WHERE user_id = :userId AND course = :courseCode AND section = :section AND result = 'submitted' AND round = :round";
        
        // Note: It's update and not add because we combine into one table.
        //$sql = 'UPDATE user SET gender=:gender, password=:password, name=:name WHERE username=:username';

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':amount', $amount, PDO::PARAM_STR);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);
		
		$isUpdateOk = False;
        if ($query->execute()) {
            $isUpdateOk = True;
    ***REMOVED***

        return $isUpdateOk;
***REMOVED***


    public function getCompletedCourses($userId) {
		$sql = "SELECT * FROM course_completed WHERE user_id = :userId";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		// Returns my result set on success.
		return $result;
***REMOVED***

    public function updateDayOfWeek($query) {
        $dayOfWeek = array(
            1 => "Monday",
            2 => "Tuesday",
            3 => "Wednesday",
            4 => "Thursday",
            5 => "Friday",
            6 => "Saturday",
            7 => "Sunday"
        );

        if (isset($query['day'])) {
            $query['day'] = $dayOfWeek[$query['day']];
            return $query;
    ***REMOVED***

        if (count($query)) {
            for ($i = 0; $i < count($query); $i++) {
                $query[$i]['day'] = $dayOfWeek[$query[$i]['day']];
        ***REMOVED***
    ***REMOVED***

        return $query;
***REMOVED***

    public function retrieveResults($userId) {

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();
        
        $sql = "SELECT course, result FROM bids WHERE user_id = :userId  AND result = '-' or result = 'in' or result = 'out'";
		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

		return $result;
***REMOVED***

    public function removeAll() {
        $sql = 'TRUNCATE TABLE bids';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
***REMOVED*** 
}