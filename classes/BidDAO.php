***REMOVED***

class BidDAO
{
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
    public function retrieveAllBidsByUser($userId, $round)
    {

        if ($round == 1) $sql = "SELECT user_id, amount, bids.course, bids.section, result, round, courses.school, courses.title, sections.day, sections.start, sections.end, sections.instructor, sections.venue, sections.size FROM bids, courses, sections WHERE bids.course = courses.course AND bids.course = sections.course AND bids.section = sections.section AND user_id = :userId AND round = :round AND result = '-'";
        else  $sql = "SELECT user_id, amount, bids.course, bids.section, result, round, courses.school, courses.title, sections.day, sections.start, sections.end, sections.instructor, sections.venue, sections.size FROM bids, courses, sections WHERE bids.course = courses.course AND bids.course = sections.course AND bids.section = sections.section AND user_id = :userId AND round = :round AND result IN ('in', 'out')";
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
    public function retrieveAllCartItemsByUser($userId, $round)
    {
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
    public function retrieveCartItemsByCodeAndSection($userId, $courseCode, $section, $round)
    {
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

    public function retrieveBidsByCodeAndSection($userId, $courseCode, $section, $round)
    {
        if ($round == 1) $sql = "SELECT user_id, amount, bids.course, bids.section, result, round, courses.school, courses.title, sections.day, sections.start, sections.end, sections.instructor, sections.venue, sections.size FROM bids, courses, sections WHERE bids.course = courses.course AND bids.course = sections.course AND bids.section = sections.section AND user_id = :userId AND bids.course = :courseCode AND bids.section = :section AND round = :round AND result = '-'";
        else $sql = "SELECT user_id, amount, bids.course, bids.section, result, round, courses.school, courses.title, sections.day, sections.start, sections.end, sections.instructor, sections.venue, sections.size FROM bids, courses, sections WHERE bids.course = courses.course AND bids.course = sections.course AND bids.section = sections.section AND user_id = :userId AND bids.course = :courseCode AND bids.section = :section AND round = :round";

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

    public function retrieveCompletedCourses()
    {
        $sql = "SELECT * FROM courses_completed ORDER BY course, user_id";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

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
    public function checkIfAddedToCart($userId, $courseCode, $section, $round)
    {
        $sql = "SELECT * FROM bids WHERE user_id = :userId AND course = :courseCode AND section = :section AND ((round = :round AND (result = 'cart' OR result = '-')) OR result = 'in')";

        if ($round == 2) $sql = "SELECT * FROM bids WHERE user_id = :userId AND course = :courseCode AND section = :section AND round = :round AND result IN ('in', 'out', 'cart')";

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
    public function deleteCartItemByCodeAndSection($userId, $courseCode, $section, $round)
    {
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

    // For /app/json/dump
    public function retrieveAllPrerequisites()
    {
        $sql = "SELECT course, prerequisite FROM prerequisites ";
        $sql .= "ORDER BY course, prerequisite";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);

        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
***REMOVED***

    // For /app/json/dump
    public function retrieveAllBids($round = 1, $retrieveAll = false)
    {
        if ($retrieveAll) {
            $sql = "SELECT user_id AS userid, amount, course, section FROM bids WHERE round = :round ";
    ***REMOVED*** else {
            $sql = "SELECT user_id AS userid, amount, course, section FROM bids WHERE round = :round AND result = '-' ";
    ***REMOVED***
        $sql .= "ORDER BY course, section, amount DESC, userid";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':round', $round, PDO::PARAM_STR);

        $query->execute();

        $result = [];

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $row['amount'] = (float)$row['amount'];
            $result[] = $row;
    ***REMOVED***

        return $result;
***REMOVED***

    public function getSuccessfulBid($userId, $course, $round = 0)
    {
        $sql = "SELECT * FROM bids WHERE result = 'in' AND course = :course AND user_id = :userId";
        if ($round) $sql .= " AND round = :round";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':course', $course, PDO::PARAM_STR);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        if ($round) $query->bindParam(':round', $round, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $result;
***REMOVED***

    // For /app/json/dump
    public function retrieveAllSuccessfulBids($round = 0, $course = null, $section = null)
    {
        // See https://wiki.smu.edu.sg/is212/Project#Dump_.28Section.29
        $text = " course, section, ";
        if ($course and $section) $text = "";
        if ($round == 1) {
            $sql = "SELECT user_id AS userid, $text amount FROM bids WHERE result = 'in' AND round = 1 ";
    ***REMOVED*** elseif ($round == 2) {
            $sql = "SELECT user_id AS userid, $text amount FROM bids WHERE result = 'in' AND round IN (1, 2) ";
    ***REMOVED*** else {
            $sql = "SELECT user_id AS userid, $text amount FROM bids WHERE result = 'in' ";
    ***REMOVED***
        if ($course) $sql .= " AND course = :course ";
        if ($section) $sql .= " AND section = :section ";
        $sql .= "ORDER BY course, userid";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        //if ($round) $query->bindParam(':round', $round, PDO::PARAM_STR);
        if ($course) $query->bindParam(':course', $course, PDO::PARAM_STR);
        if ($section) $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->execute();
        $result = [];

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $row['amount'] = (float)$row['amount'];
            $result[] = $row;
    ***REMOVED***

        return $result;
***REMOVED***

    public function checkDuplicates($userId, $courseSections, $round)
    {
        $selectedCourses = array_column($courseSections, 'course');
        $duplicates = array_unique(array_diff_assoc($selectedCourses, array_unique($selectedCourses)));

        if (count($duplicates)) {
            return true;
    ***REMOVED***

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $sql = "SELECT course FROM bids WHERE user_id = :userId AND course IN ('" . implode('\', \'', $selectedCourses) . "') AND ((round = :round AND result = '-') OR result = 'in')";

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);

        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        // Meaning it already exists in our bids.
        if ($query->rowCount()) {
            return true;
    ***REMOVED***

        return false;
***REMOVED***

    /*
***REMOVED***Check if bidded course is same as user's current school, only used in Round 1.
***REMOVED***"not own school course"	This only happens in round 1 where students are allowed to bid for modules from their own school.
***REMOVED***bid.csv validation 1/7
***REMOVED***
    public function checkOwnSchoolCourse($userSchool, $courseCode)
    {
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
    public function checkTimetableConflicts($userId, $courseSections, $round)
    {
        $inClauseBuilder = "";
        // SANITISE INPUTS PLSSSS!! this is vulnerable to SQL injection.
        for ($i = 0; $i < count($courseSections); $i++) {
            $inClauseBuilder .= "('{$courseSections[$i]['course']}', '{$courseSections[$i]['section']}')";

            if ($i != (count($courseSections) - 1)) {
                $inClauseBuilder .= ", ";
        ***REMOVED***
    ***REMOVED***

        $sql = "SELECT course, day, start, end FROM sections WHERE (course, section) IN (" . $inClauseBuilder . ") ";
        $sql .= "UNION SELECT course, day, start, end FROM sections WHERE (course, section) IN (SELECT course, section FROM bids WHERE user_id = :userId AND ((round = :round AND result = '-') OR result = 'in')) ";
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
                    if ($timeslot1['end'] > $timeslot2['start']) {
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
    public function checkExamConflicts($userId, $courseSections, $round)
    {
        $courses = array();
        for ($i = 0; $i < count($courseSections); $i++) {
            array_push($courses, $courseSections[$i]['course']);
    ***REMOVED***

        $sql = "SELECT course, exam_date, exam_start, exam_end FROM courses WHERE course IN (\"" . implode("\", \"", $courses) . "\") ";
        $sql .= "UNION SELECT course, exam_date, exam_start, exam_end FROM courses WHERE course IN (SELECT course FROM bids WHERE user_id = :userId AND ((round = :round AND result = '-') OR result = 'in')) ";
        $sql .= "ORDER BY exam_date, exam_start";

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
                if ($timeslot1['exam_date'] == $timeslot2['exam_date']) {
                    if ($timeslot1['exam_end'] > $timeslot2['exam_start']) {
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
    public function hasPrerequisites($courseCode)
    {
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
    public function hasCompletedPrerequisites($userId, $courseCode)
    {
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $sql = "SELECT * FROM courses_completed WHERE user_id = :userId AND course IN (SELECT prerequisite FROM prerequisites WHERE course = :courseCode)";
        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $sql2 = "SELECT prerequisite FROM prerequisites WHERE course = :courseCode";
        $query2 = $db->prepare($sql2);
        $query2->setFetchMode(PDO::FETCH_ASSOC);
        $query2->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query2->execute();
        $result2 = $query2->fetchAll(PDO::FETCH_ASSOC);

        if ($query->rowCount() == $query2->rowCount()) {
            return true;
    ***REMOVED***

        return false;
***REMOVED***

    /*
***REMOVED***Check if course has already been completed. Why do you want to take the same course again, right?
***REMOVED***"course completed"	student has already completed this course.
***REMOVED***bid.csv validation 5/7
***REMOVED***
    public function hasCompletedCourse($userId, $courseCode)
    {
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
    public function countBids($userId, $round)
    {
        $sql = "SELECT * FROM bids WHERE user_id = :userId AND  ((round = :round AND result = '-') OR result = 'in')";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);

        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        // Returns my result set on success.
        return count($result);
***REMOVED***

    /*
***REMOVED***Get user's edollar.
***REMOVED***"not enough e-dollar"	student has not enough e-dollars to place the bid. If it is an update of a previous bid for the same course, account for the e$ gained back from the cancellation
***REMOVED***bid.csv validation 7/7
***REMOVED***
    public function getEDollar($userId)
    {
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

    public function addToCart($userId, $courseCode, $section, $round)
    {
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

    /*public function addBidViaCart($userId, $courseCode, $section, $amount, $round)
    {
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        try {
            // We start our transaction.
            $db->beginTransaction();

            // Note: It's update and not add because our bids/cart info etc is all in one bids table.
            if ($round == 1) {
                $sql = "UPDATE bids SET result = '-', amount = :amount WHERE user_id = :userId AND course = :courseCode AND section = :section AND result = 'cart' AND round = :round";
                //$sql = "INSERT INTO bids (user_id, course, section, amount, round) VALUES (:userId, :courseCode, :section, :amount, :round) ON DUPLICATE KEY UPDATE amount = :amount2, result = '-'";

        ***REMOVED*** elseif ($round == 2) {
                // Edited 24 Oct.
                $this->addBid($userId, $courseCode, $section, $amount, $round);
                return true;
        ***REMOVED***
            $query = $db->prepare($sql);
            $query->bindParam(':amount', $amount, PDO::PARAM_STR);
            $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
            $query->bindParam(':section', $section, PDO::PARAM_STR);
            $query->bindParam(':userId', $userId, PDO::PARAM_STR);
            $query->bindParam(':round', $round, PDO::PARAM_STR);

            $query->execute();

            // We begin our second transaction.
            $sql = "UPDATE users SET edollar = edollar - :amount WHERE user_id = :userId";

            $query = $db->prepare($sql);
            $query->bindParam(':amount', $amount, PDO::PARAM_STR);
            $query->bindParam(':userId', $userId, PDO::PARAM_STR);

            $query->execute();

            // We've got this far without an exception, so commit the changes.
            $db->commit();
            return true;
    ***REMOVED*** catch (Exception $e) {
            $db->rollback();

            return false;
    ***REMOVED***
***REMOVED****/

    public function updateBid($userId, $courseCode, $section, $amount, $round)
    {
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $sql = "SELECT amount FROM bids WHERE course = :courseCode AND section = :section AND user_id = :userId AND round = :round";

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);
        $query->execute();

        $currentAmount = $query->fetch(PDO::FETCH_ASSOC);

        if ($query->rowCount()) {
            $sql = "UPDATE bids SET amount = :amount WHERE user_id = :userId AND course = :courseCode AND section = :section AND round = :round";

            $query = $db->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->bindParam(':amount', $amount, PDO::PARAM_STR);
            $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
            $query->bindParam(':section', $section, PDO::PARAM_STR);
            $query->bindParam(':userId', $userId, PDO::PARAM_STR);
            $query->bindParam(':round', $round, PDO::PARAM_STR);
            $query->execute();

            $difference = $amount - $currentAmount['amount']; // DIRECTION MATTERS HERE!

            $sql = "UPDATE users SET edollar = edollar - (:amount) WHERE user_id = :userId"; // Because it will affect this.

            $query = $db->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->bindParam(':userId', $userId, PDO::PARAM_STR);
            $query->bindParam(':amount', $difference, PDO::PARAM_STR);

            $roundDAO = new RoundDAO();
            $currentRound = $roundDAO->getCurrentRound()['round'];
            if ($currentRound == 2) {
                $roundClearingDAO = new RoundClearingDAO();
                $roundClearingDAO->clearCourseSection($courseCode, $section);
        ***REMOVED***

            $isUpdateOk = False;
            if ($query->execute()) {
                $isUpdateOk = True;
        ***REMOVED***

            return $isUpdateOk;
    ***REMOVED***
***REMOVED***


    public function getCompletedCourses($userId)
    {
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

    public function updateDayOfWeek($query)
    {
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

    public function retrieveResults($userId)
    {

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $sql = "SELECT * FROM bids WHERE user_id = :userId AND (result = '-' OR result = 'in' OR result = 'out')";
        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);

        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
***REMOVED***

    public function removeAll()
    {
        $sql = 'TRUNCATE TABLE bids';

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);

        $stmt->execute();
        $count = $stmt->rowCount();
***REMOVED***

    public function getAllBidsForCalendar($userId, $round)
    {
        $sql = "SELECT bids.course, bids.section, result, round, courses.school, courses.title, sections.day, sections.start, sections.end, sections.instructor, sections.venue, sections.size FROM bids, courses, sections WHERE bids.course = courses.course AND bids.course = sections.course AND bids.section = sections.section AND user_id = :userId AND  ((round = :round AND result = '-') OR result = 'in')";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);

        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
***REMOVED***


    public function refundbidamount($userId, $courseCode, $section = null, $round = 1)
    {
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        if ($section) {
            $sql = "SELECT section, amount FROM bids WHERE course = :courseCode AND section = :section AND user_id = :userId AND round = :round AND result NOT IN ('out', 'cart')"; // AND result = '-'
    ***REMOVED*** else {
            $sql = "SELECT section, amount FROM bids WHERE course = :courseCode AND user_id = :userId AND round = :round AND result NOT IN ('out', 'cart')"; // AND result = '-'
    ***REMOVED***

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);

        if ($section) {
            $query->bindParam(':section', $section, PDO::PARAM_STR);
    ***REMOVED***

        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($query->rowCount()) {
            $sql = "UPDATE users SET edollar = edollar + (:amount) WHERE user_id = :userId";

            $query = $db->prepare($sql);
            $query->bindParam(':userId', $userId, PDO::PARAM_STR);
            $query->bindParam(':amount', $result['amount'], PDO::PARAM_STR);
            $query->execute();

            if ($section) {
                $sql = "DELETE FROM bids WHERE course = :courseCode AND section = :section AND user_id = :userId AND round = :round"; // AND result = '-'
        ***REMOVED*** else {
                $sql = "DELETE FROM bids WHERE course = :courseCode AND user_id = :userId AND round = :round"; // AND result = '-'
        ***REMOVED***

            $query = $db->prepare($sql);
            $query->bindParam(':userId', $userId, PDO::PARAM_STR);
            $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
            $query->bindParam(':round', $round, PDO::PARAM_STR);
            if ($section) $query->bindParam(':section', $section, PDO::PARAM_STR);
            $query->execute();

            $roundDAO = new RoundDAO();
            $currentRound = $roundDAO->getCurrentRound()['round'];
            if ($currentRound == 2) {
                $roundClearingDAO = new RoundClearingDAO();
                $roundClearingDAO->clearCourseSection($courseCode, $result['section']);
        ***REMOVED***

            return true;
    ***REMOVED*** else {
            return false;
    ***REMOVED***
***REMOVED***

    public function addBid($userId, $courseCode, $section, $amount, $round = 1)
    {
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $existingBid = $this->findExistingBid($userId, $courseCode, $round);

        //$sql = "INSERT INTO bids (user_id, course, section, amount, round) VALUES (:userId, :courseCode, :section, :amount, :round) ON DUPLICATE KEY UPDATE amount = :amount2";
        $sql = "INSERT INTO bids (user_id, course, section, amount, round) VALUES (:userId, :courseCode, :section, :amount, :round) ON DUPLICATE KEY UPDATE amount = :amount2, result = '-'";

        $query = $db->prepare($sql);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':amount', $amount, PDO::PARAM_STR);
        $query->bindParam(':amount2', $amount, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);

        $query->execute();

        $amountToDebit = $amount;

        // If there's an existing bid, we find out the bid amount difference.
        if ($existingBid) {
            $previousAmount = $existingBid['amount'];
            $amountToDebit = $amount - $previousAmount;
    ***REMOVED***

        $sql = "UPDATE users SET edollar = edollar - (:amount) WHERE user_id = :userId";

        $query = $db->prepare($sql);
        $query->bindParam(':amount', $amountToDebit, PDO::PARAM_STR);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);

        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        // Only if round 2.
        if ($round == 2) {
            $roundClearingDAO = new RoundClearingDAO();
            $roundClearingDAO->clearCourseSection($courseCode, $section);
    ***REMOVED***

        return $query->rowCount();
***REMOVED***

    public function findExistingBid($userId, $courseCode, $round = 1)
    {
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $sql = "SELECT * FROM bids WHERE user_id = :userId AND course = :courseCode AND ((round = :round AND result = '-') OR (round = 2 AND result = 'in'))";
        $query = $db->prepare($sql);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $result;
***REMOVED***

    public function getCourseByCode($userId, $courseCode, $round = 1)
    {
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $sql = "SELECT user_id, course, section, amount FROM bids WHERE user_id = :userId AND course = :courseCode AND section = :section AND ((round = :round AND result = '-') OR result = 'in')";
        $query = $db->prepare($sql);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $result;
***REMOVED***

    public function getCourseByCodeAndSection($courseCode, $section)
    {
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $sql = "SELECT * FROM sections WHERE course = :courseCode AND section = :section";
        $query = $db->prepare($sql);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $result;
***REMOVED***

    public function insertOrUpdateMinBid($courseCode, $section, $amount)
    {
        $sql = "INSERT INTO minbid (course, section, bidAmount) VALUES (:courseCode, :section, :amount) ON DUPLICATE KEY UPDATE bidAmount = :amount2";

        $minBidRecord = $this->getMinBid($courseCode, $section);
        $minBid = 10;

        if ($minBidRecord) {
            $minBid = $minBidRecord['bidAmount'];
    ***REMOVED***

        // Min bid will never be lower. If it's lower, remain the same.
        if ($amount < $minBid) {
            $amount = $minBid;
    ***REMOVED***

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindParam(':amount2', $amount, PDO::PARAM_STR);

        $result = false;

        if ($stmt->execute()) {
            $result = true;
    ***REMOVED***
        return $result;
***REMOVED***

    public function getBidsCountByCourseSection($courseCode, $section, $round = 0)
    {
        $sql = "SELECT COUNT(*) AS bids FROM bids WHERE course = :courseCode AND section = :section";

        if ($round) {
            $sql .= " AND round = :round";
    ***REMOVED***

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();
        $query = $db->prepare($sql);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        if ($round) $query->bindParam(':round', $round, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $result['bids']; // CANNOT do $query->rowCount();
***REMOVED***

    //Retrieve number of Successful bids of all user of a particular course code
    public function getSuccessfulByCourseCode($courseCode, $section, $round = 0)
    {
        //$sql = "SELECT * FROM bids AS bd INNER JOIN sections as sc ON bd.course = sc.course AND bd.section = sc.section  WHERE result = 'in' and bd.course = :courseCode and bd.section = :sectionRQ";
        $sql = "SELECT COUNT(*) AS bids FROM bids WHERE course = :courseCode AND section = :section AND result = 'in'";

        if ($round) {
            $sql .= " AND round = :round";
    ***REMOVED***

        // Edited on 24 Oct.
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();
        $query = $db->prepare($sql);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        if ($round) $query->bindParam(':round', $round, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $result['bids']; // CANNOT do $query->rowCount();
***REMOVED***

    public function insertMinBidforAllCourses($courseCode, $section, $minbid, $user_id)
    {
        $sql = "INSERT INTO minbid (course, section, bidAmount, user_id) VALUES (:courseCode,:section, :minbid, :user_id)";

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->bindParam(':minbid', $minbid, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);

        $result = false;

        if ($stmt->execute()) {
            $result = True;
    ***REMOVED***
        return $result;
***REMOVED***

    public function updateMinBidforAllCourses($courseCode, $section, $minbid, $user_id)
    {
        $sql = "UPDATE minbid SET bidAmount = :minbid WHERE course = :courseCode AND section = :section AND user_id = :user_id";

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->bindParam(':minbid', $minbid, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);


        $result = false;

        if ($stmt->execute()) {
            $result = True;
    ***REMOVED***
        return $result;
***REMOVED***

    public function getMinBid($courseCode, $section)
    {
        $sql = "SELECT * FROM minbid WHERE course =:courseCode AND section = :section";
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
***REMOVED***

    public function getMinBidWithCourseCode($courseCode, $section)
    {
        $sql = "SELECT MIN(amount) AS  minAmount FROM bids WHERE course =:courseCode AND result = 'in'  AND round = 2 AND section = :section";
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        $query = $conn->prepare($sql);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result['minAmount'] != "") {
            $val = floatval($result['minAmount']) + 1.00;
            return $val;
    ***REMOVED***
        return 10;


***REMOVED***

    //This functions Retrieve ID of min Bid User
    public function getIdofMinBidUser($courseCode, $section, $minVal)
    {
        $minVal = (int)$minVal - 1;
        // Call DB Connection
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        //Set SQL Command
        $sql = "SELECT * FROM `bids` AS bd WHERE bd.course = :courseCode AND section = :section AND round = 2 AND amount = :amt AND result = 'in'";
        $query = $conn->prepare($sql);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->bindParam(':amt', $minVal, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;

***REMOVED***

    //This functions Update user's Bid for Round 2 Clearing
    public function updateUserBid($userId, $courseCode, $section)
    {
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();
        try {
            // We start our transaction.
            $db->beginTransaction();
            $sql = "UPDATE bids SET result = 'out' WHERE course = :courseCode AND section = :section AND result = 'in' AND round = 2 AND user_id = :userId";
            $query = $db->prepare($sql);
            $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
            $query->bindParam(':section', $section, PDO::PARAM_STR);
            $query->bindParam(':userId', $userId, PDO::PARAM_STR);
            $query->execute();
            // We've got this far without an exception, so commit the changes.
            $db->commit();
            return true;
    ***REMOVED*** catch (Exception $e) {
            $db->rollback();

            return false;

    ***REMOVED***

***REMOVED***

    public function refundBidUser($minVal, $userId)
    {
        $minVal = (int)$minVal - 1;
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();
        $sql = "UPDATE users SET edollar = edollar + :amount WHERE user_id = :userId";
        $query = $db->prepare($sql);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':amount', $minVal, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;

***REMOVED***

    public function dropBid($bidId, $userId)
    {
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();
        try {
            // We start our transaction.
            $db->beginTransaction();
            $sql = "SELECT * from bids WHERE id = :bidId";
            $query = $db->prepare($sql);
            $query->bindParam(':bidId', $bidId, PDO::PARAM_STR);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $sql = "DELETE FROM bids WHERE id = :bidId";
            $query = $db->prepare($sql);
            $query->bindParam(':bidId', $bidId, PDO::PARAM_STR);
            $query->execute();
            $sql = "UPDATE users SET edollar = edollar + (:amount) WHERE user_id = :userId";
            $query = $db->prepare($sql);
            $query->bindParam(':amount', $result['amount'], PDO::PARAM_STR);
            $query->bindParam(':userId', $userId, PDO::PARAM_STR);
            $query->execute();
            // We've got this far without an exception, so commit the changes.
            $db->commit();
            return true;
    ***REMOVED*** catch (Exception $e) {
            $db->rollBack();
    ***REMOVED***

***REMOVED***

    public function retrieveBidsDump($section, $course, $round)
    {
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();
        $sql = "SELECT (ROW_NUMBER() OVER(ORDER BY user_id)) AS 'row', user_id AS userid, amount, result FROM bids WHERE course = :course AND section = :section AND round=:round ORDER BY amount ASC, user_id ASC";
        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':course', $course, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->bindParam(':round', $round, PDO::PARAM_STR);
        $query->execute();
        $result = [];

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $row['amount'] = (float)$row['amount'];
            $result[] = $row;
    ***REMOVED***
        return $result;
***REMOVED***
}