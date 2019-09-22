***REMOVED***

class BidDAO {
    /*
       States:
       Bid statuses in cart = {cart, submitted}
       On round start status = {-} but only use submitted for processing
       After round proc status = {in, out}
    */
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

    public function retrieveCartItemsByCodeAndSection($userId, $coursecourse, $section, $round) {
		$sql = "SELECT user_id, amount, bids.course, bids.section, result, round, courses.school, courses.title, sections.day, sections.start, sections.end, sections.instructor, sections.venue, sections.size FROM bids, courses, sections WHERE bids.course = courses.course AND bids.course = sections.course AND bids.section = sections.section AND user_id = :userId AND bids.course = :courseCode AND bids.section = :section AND round = :round AND result = 'cart'";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':courseCode', $coursecourse, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        //$result = $this->updateDayOfWeek($result);
		
		// Returns my result set on success.
		return $result;
***REMOVED***

    public function deleteCartItemByCodeAndSection($userId, $coursecourse, $section, $round) {
		$sql = "DELETE FROM bids WHERE course = :courseCode AND section = :section AND user_id = :userId AND round = :round AND result = 'cart'";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':courseCode', $coursecourse, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        //$result = $this->updateDayOfWeek($result);
		
		// Returns my result set on success.
		$isDeleteOK = false;

        if ($query->execute()) {
            $isDeleteOK = true;
    ***REMOVED***

        return $isDeleteOK;
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

        $sql = "SELECT course, day, start, end FROM sections WHERE (course, section) IN (" . $inClauseBuilder . ")  ";
        $sql .= "UNION SELECT course, day, start, end FROM sections WHERE (course, section) IN (SELECT course, section FROM bids WHERE user_id = :userId AND result = 'submitted')";
        $sql .= " ORDER BY day, start";
        
        // sort by Day, then search
		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);

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

    public function checkIfAddedToCart($userId, $courseCode, $section, $round) {
		$sql = "SELECT * FROM bids WHERE user_id = :userId AND course = :courseCode AND section = :section AND round = :round AND (result = 'cart' OR result = 'submitted')";

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

    public function countBids($userId, $round) {
		$sql = "SELECT * FROM bids WHERE user_id = :userId AND result = 'cart' AND round = :round";

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
}