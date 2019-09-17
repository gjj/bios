***REMOVED***

class BidDAO {
    /*
       States:
       Bid statuses in cart = {cart, submitted}
       On round start status = {-} but only use submitted for processing
       After round proc status = {in, out}
    */
    public function retrieveAllBidsByUser($userId, $round) {
		$sql = "SELECT user_id, amount, code, bids.section, result, round, courses.school, courses.title, sections.day, sections.start, sections.end, sections.instructor, sections.venue, sections.size FROM bids, courses, sections WHERE bids.code = courses.course AND bids.code = sections.course AND bids.section = sections.section AND user_id = :userId AND round = :round AND result = 'cart' OR result = 'submitted'";

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

    public function checkIfAddedToCart($userId, $courseCode, $section, $round) {
		$sql = "SELECT * FROM bids WHERE user_id = :userId AND code = :courseCode AND section = :section AND round = :round AND result = 'cart' OR result = 'submitted'";

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

    public function addToCart($userId, $courseCode, $section, $round) {
        $sql = "INSERT INTO bids (user_id, code, section, result, round) VALUES (:userId, :code, :section, 'cart', :round)";
        
        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();
         
        $stmt = $conn->prepare($sql); 

        $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
        $stmt->bindParam(':code', $courseCode, PDO::PARAM_STR);
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
        $query->bindParam(':userId', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		// Returns my result set on success.
		return $result;
***REMOVED***

    public function getCompletedCourses($userId) {
		$sql = "SELECT * FROM course_completed WHERE user_id = :userId";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $courseCode, PDO::PARAM_STR);

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
        if (count($query)) {
            for ($i = 0; $i < count($query); $i++) {
                $query[$i]['day'] = $dayOfWeek[$query[$i]['day']];
        ***REMOVED***
    ***REMOVED***

        return $query;
***REMOVED***
}