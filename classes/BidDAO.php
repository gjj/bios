<?php

class BidDAO
{
    /*
       Bid statuses:
       Bid statuses in cart = {cart, submitted}
       On round start status = {-} but only use submitted for processing
       After round proc status = {in, out}
    */

    /**
     * This methods returns all BIDS by user.
     * @params $userId The user ID to search for.
     * @params $round The current round.
     * @return All BIDS that matches the search params.
     */
    public function retrieveAllBidsByUser($userId, $round)
    {
        $sql = "SELECT user_id, amount, bids.course, bids.section, result, round, courses.school, courses.title, sections.day, sections.start, sections.end, sections.instructor, sections.venue, sections.size FROM bids, courses, sections WHERE bids.course = courses.course AND bids.course = sections.course AND bids.section = sections.section AND user_id = :userId AND round = :round AND result = '-'";

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
    }


    /**
     * This methods returns all cart items by user of a given round
     * @params $userId The user ID to search for.
     * @params $round The current round.
     * @return All cart items that matches the search params.
     */
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
    }

    /**
     * This methods returns all cart items by a user given the code and section.
     * @params $userId The user ID to search for.
     * @params $coursecode The course code.
     * @params $section The section.
     * @params $round The current round.
     * @return All cart items that matches the search params.
     */
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
    }

    public function retrieveBidsByCodeAndSection($userId, $courseCode, $section, $round)
    {
        $sql = "SELECT user_id, amount, bids.course, bids.section, result, round, courses.school, courses.title, sections.day, sections.start, sections.end, sections.instructor, sections.venue, sections.size FROM bids, courses, sections WHERE bids.course = courses.course AND bids.course = sections.course AND bids.section = sections.section AND user_id = :userId AND bids.course = :courseCode AND bids.section = :section AND round = :round AND result = '-'";

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
    }

    /**
     * Check if the course and section pair has already been added to cart.
     * @params $userId The user ID to search for.
     * @params $coursecode The course code.
     * @params $section The section.
     * @params $round The current round.
     * @return true if already added to cart, false if not in the cart.
     */
    public function checkIfAddedToCart($userId, $courseCode, $section, $round)
    {
        $sql = "SELECT * FROM bids WHERE user_id = :userId AND course = :courseCode AND section = :section AND ((round = :round AND (result = 'cart' OR result = '-')) OR result = 'in')";

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
        }

        return false;
    }

    /**
     * Delete cart item.
     * @params $userId The user ID to search for.
     * @params $coursecode The course code.
     * @params $section The section.
     * @params $round The current round.
     * @return true if successfully deleted, false if not.
     */
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
        }

        return $isDeleteOK;
    }

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
    }

    // For /app/json/dump
    public function retrieveAllBids($round = 1, $retrieveAll = false)
    {
        if ($retrieveAll) {
            $sql = "SELECT user_id AS userid, amount, course, section FROM bids WHERE round = :round ";
        } else {
            $sql = "SELECT user_id AS userid, amount, course, section FROM bids WHERE round = :round AND result = '-' ";
        }
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
        }

        return $result;
    }

    public function getSuccessfulBid($userId, $course)
    {
        $sql = "SELECT * FROM bids WHERE result = 'in' AND course = :course AND user_id = :userId;";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':course', $course, PDO::PARAM_STR);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    // For /app/json/dump
    public function retrieveAllSuccessfulBids($round = 0, $course = null, $section = null)
    {
        // See https://wiki.smu.edu.sg/is212/Project#Dump_.28Section.29
        $text = " course, section, ";
        if ($course and $section) $text = "";
        if ($round) {
            $sql = "SELECT user_id AS userid, $text amount FROM bids WHERE result = 'in' AND round = :round ";
        } else {
            $sql = "SELECT user_id AS userid, $text amount FROM bids WHERE result = 'in' ";
        }
        if ($course) $sql .= " AND course = :course ";
        if ($section) $sql .= " AND section = :section ";
        $sql .= "ORDER BY course, userid";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        if ($round) $query->bindParam(':round', $round, PDO::PARAM_STR);
        if ($course) $query->bindParam(':course', $course, PDO::PARAM_STR);
        if ($section) $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->execute();
        $result = [];

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $row['amount'] = (float)$row['amount'];
            $result[] = $row;
        }

        return $result;
    }

    public function checkDuplicates($userId, $courseSections, $round)
    {
        $selectedCourses = array_column($courseSections, 'course');
        $duplicates = array_unique(array_diff_assoc($selectedCourses, array_unique($selectedCourses)));

        if (count($duplicates)) {
            return true;
        }

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
        }

        return false;
    }

    /*
     * Check if bidded course is same as user's current school, only used in Round 1.
     * "not own school course"	This only happens in round 1 where students are allowed to bid for modules from their own school.
     * bid.csv validation 1/7
     */
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
        }

        return false;
    }

    /*
     * Check for existing cart bids (passed as $coursesections) WITH existing records (i.e. got into the course/submitted bids) for timetable conflicts.
     * "class timetable clash"	The class timeslot for the section clashes with that of a previously bidded section.
     * bid.csv validation 2/7
     */
    public function checkTimetableConflicts($userId, $courseSections, $round)
    {
        $inClauseBuilder = "";
        // SANITISE INPUTS PLSSSS!! this is vulnerable to SQL injection.
        for ($i = 0; $i < count($courseSections); $i++) {
            $inClauseBuilder .= "('{$courseSections[$i]['course']}', '{$courseSections[$i]['section']}')";

            if ($i != (count($courseSections) - 1)) {
                $inClauseBuilder .= ", ";
            }
        }

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
                    }
                }
            }
        }

        return false;
    }

    /*
     * Check for existing cart bids (passed as $courseSections) WITH existing records (i.e. got into the course/submitted bids) for exam conflicts.
     * "exam timetable clash"	The exam timeslot for this section clashes with that of a previously bidded section.
     * bid.csv validation 3/7
     */
    public function checkExamConflicts($userId, $courseSections, $round)
    {
        $courses = array();
        for ($i = 0; $i < count($courseSections); $i++) {
            array_push($courses, $courseSections[$i]['course']);
        }

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
                    }
                }
            }
        }

        return false;
    }

    /*
     * Check if user has completed the prerequisites.
     * "incomplete prerequisites"	student has not completed the prerequisites for this course.
     * bid.csv validation 4/7
     */
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
        }

        return false;
    }

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
        }

        return false;
    }

    /*
     * Check if course has already been completed. Why do you want to take the same course again, right?
     * "course completed"	student has already completed this course.
     * bid.csv validation 5/7
     */
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
        }

        return false;
    }

    /*
     * Return count(confirmed mods + submitted bids).
     * "section limit reached"	student has already bidded for 5 sections.
     * bid.csv validation 6/7
     */
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
    }

    /*
     * Get user's edollar.
     * "not enough e-dollar"	student has not enough e-dollars to place the bid. If it is an update of a previous bid for the same course, account for the e$ gained back from the cancellation
     * bid.csv validation 7/7
     */
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
    }

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
        }

        return $isAddOK;
    }

    public function addBid($userId, $courseCode, $section, $amount, $round)
    {
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        try {
            // We start our transaction.
            $db->beginTransaction();

            // Note: It's update and not add because our bids/cart info etc is all in one bids table.
            if($round == 1){
                $sql = "UPDATE bids SET result = '-', amount = :amount WHERE user_id = :userId AND course = :courseCode AND section = :section AND result = 'cart' AND round = :round";
 
            }
            else{
                $sql = "UPDATE bids SET result = 'in', amount = :amount WHERE user_id = :userId AND course = :courseCode AND section = :section AND result = 'cart' AND round = :round";
            }
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
        } catch (Exception $e) {
            $db->rollback();

            return false;
        }
    }

    public function updateBid($userId, $courseCode, $section, $amount, $round)
    {
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $sql = "SELECT amount FROM bids WHERE course = :courseCode AND section = :section AND user_id = :userId AND result = '-'";

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->execute();

        $currentAmount = $query->fetch(PDO::FETCH_ASSOC);

        if ($query->rowCount()) {
            $sql = "UPDATE bids SET amount = :amount WHERE user_id = :userId AND course = :courseCode AND section = :section AND result = '-' AND round = :round";

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

            $isUpdateOk = False;
            if ($query->execute()) {
                $isUpdateOk = True;
            }

            return $isUpdateOk;
        }
    }


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
    }

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
        }

        if (count($query)) {
            for ($i = 0; $i < count($query); $i++) {
                $query[$i]['day'] = $dayOfWeek[$query[$i]['day']];
            }
        }

        return $query;
    }

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
    }

    public function removeAll()
    {
        $sql = 'TRUNCATE TABLE bids';

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);

        $stmt->execute();
        $count = $stmt->rowCount();
    }

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
    }


    public function refundbidamount($userId, $courseCode, $section = null)
    {
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        if ($section) {
            $sql = "SELECT amount FROM bids WHERE course = :courseCode AND section = :section AND user_id = :userId AND result = '-'";
        } else {
            $sql = "SELECT amount FROM bids WHERE course = :courseCode AND user_id = :userId AND result = '-'";
        }

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);

        if ($section) {
            $query->bindParam(':section', $section, PDO::PARAM_STR);
        }

        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($query->rowCount()) {
            $sql = "UPDATE users SET edollar = edollar + (:amount) WHERE user_id = :userId";

            $query = $db->prepare($sql);
            $query->bindParam(':userId', $userId, PDO::PARAM_STR);
            $query->bindParam(':amount', $result['amount'], PDO::PARAM_STR);
            $query->execute();

            if ($section) {
                $sql = "DELETE FROM bids WHERE course = :courseCode AND section = :section AND user_id = :userId AND result = '-'";
            } else {
                $sql = "DELETE FROM bids WHERE course = :courseCode AND user_id = :userId AND result = '-'";
            }


            $query = $db->prepare($sql);
            $query->bindParam(':userId', $userId, PDO::PARAM_STR);
            $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
            if ($section) $query->bindParam(':section', $section, PDO::PARAM_STR);
            $query->execute();

            return true;
        } else {
            return false;
        }
    }

    public function addBidBootstrap($userId, $courseCode, $section, $amount)
    {
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $existingBid = $this->findExistingBid($userId, $courseCode);

        $sql = "INSERT INTO bids (user_id, course, section, amount) VALUES (:userId, :courseCode, :section, :amount) ON DUPLICATE KEY UPDATE amount = :amount2";
        $query = $db->prepare($sql);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':amount', $amount, PDO::PARAM_STR);
        $query->bindParam(':amount2', $amount, PDO::PARAM_STR);

        $query->execute();

        $amountToDebit = $amount;

        // If there's an existing bid, we find out the bid amount difference.
        if ($existingBid) {
            $previousAmount = $existingBid['amount'];
            $amountToDebit = $amount - $previousAmount;
        }

        $sql = "UPDATE users SET edollar = edollar - (:amount) WHERE user_id = :userId";

        $query = $db->prepare($sql);
        $query->bindParam(':amount', $amountToDebit, PDO::PARAM_STR);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);

        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $query->rowCount();
    }

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
    }

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
    }

    //Retrieve number of Successful bids of all user of a particular course code
    public function getSuccessfulByCourseCode($courseCode, $section)
    {
        $sql = "SELECT * FROM bids AS bd INNER JOIN sections as sc ON bd.course = sc.course AND bd.section = sc.section  WHERE result = 'in' and bd.course = :courseCode and bd.section = :sectionRQ";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();
        $query = $db->prepare($sql);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':sectionRQ', $section, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (($query->rowCount()) > 0) {
            return $query->rowCount();
        } else {
            return 0;
        }
    }

    public function insertMinBidforAllCourses($courseCode, $section, $minbid)
    {
        $sql = "INSERT INTO minbid (course, section, bidAmount) VALUES (:courseCode,:section, :minbid)";

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->bindParam(':minbid', $minbid, PDO::PARAM_STR);

        $result = false;

        if ($stmt->execute()) {
            $result = True;
        }
        return $result;
    }

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
        }
        return 10;


    }

    
}