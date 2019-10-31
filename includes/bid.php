***REMOVED***
require_once 'common.php';

function addOrUpdateBid($userId, $amount, $courseCode, $section)
{
    $bidDAO = new BidDAO();
    $roundDAO = new RoundDAO();
    $userDAO = new UserDAO();
    $courseDAO = new CourseDAO();
    $sectionDAO = new SectionDAO();
    $currentRound = $roundDAO->getCurrentRound()['round'];

    $errors = [];

    

    if (!$errors) {
        if (!is_numeric($amount) or $amount < 10 or $amount != round($amount, 2)) {
            $errors[] = "invalid amount";
    ***REMOVED***

        if (!$courseDAO->retrieveByCode($courseCode)) {
            $errors[] = "invalid course";
    ***REMOVED*** elseif (!$sectionDAO->retrieveByCodeAndSection($courseCode, $section)) {
            $errors[] = "invalid section";
    ***REMOVED***

        if (!$userDAO->retrieveById($userId)) {
            $errors[] = "invalid userid";
    ***REMOVED***
***REMOVED***

    // Not sure to place it here or not.
    // Round is active or not.
    if (!$roundDAO->roundIsActive()) {
        $errors[] = "round ended";
***REMOVED***
    
    // If no errors so far, then we proceed for our second round of validation checks...
    if (!$errors) {
        $existingBid = $bidDAO->findExistingBid($userId, $courseCode);

        $course = $courseDAO->retrieveByCode($courseCode);
    
        if ($currentRound == 2) {
            // "bid too low" the amount must be more than the minimum bid (only applicable for round 2)
            // check min bid + vacancy maybe?
            $minBid = $bidDAO->getMinBid($courseCode, $section);

            if ($minBid) $minBid = $minBid['bidAmount'];
            else $minBid = 10;

            if ($amount < $minBid) {
                $errors[] = "bid too low";
        ***REMOVED***
    ***REMOVED***

        if (!$existingBid) {
            $user = $userDAO->retrieveById($userId);

            // course enrolled: Student has already won a bid for a section in this course in a previous round.
            if ($bidDAO->getSuccessfulBid($userId, $course['course'], 1)) {
                $errors[] = "course enrolled";
        ***REMOVED***
            

            // Validation 1/7 not own school course: This only happens in round 1 where students are allowed to bid for modules from their own school.
            if ($user['school'] !== $course['school']) {
                $errors[] = "not own school course";
        ***REMOVED***

            // Validation 2/7 class timetable clash: The class timeslot for the section clashes with that of a previously bidded section.
            if ($bidDAO->checkTimetableConflicts($userId, [['course' => $course['course'], 'section' => $section]], $currentRound)) {
                $errors[] = "class timetable clash";
        ***REMOVED***

            // Validation 3/7 exam timetable clash: The exam timeslot for this section clashes with that of a previously bidded section.
            if ($bidDAO->checkExamConflicts($userId, [['course' => $course['course'], 'section' => $section]], $currentRound)) {
                $errors[] = "exam timetable clash";
        ***REMOVED***

            // Validation 4/7 incomplete prerequisites:	student has not completed the prerequisites for this course.
            if ($bidDAO->hasPrerequisites($course['course'])) {
                if (!$bidDAO->hasCompletedPrerequisites($userId, $course['course'])) {
                    $errors[] = "incomplete prerequisites";
            ***REMOVED***
        ***REMOVED***

            // Validation 5/7 course completed: student has already completed this course.
            if ($bidDAO->hasCompletedCourse($userId, $course['course'])) {
                $errors[] = "course completed";
        ***REMOVED***

            // course enrolled

            // Validation 6/7 section limit reached: student has already bidded for 5 sections.
            if ($bidDAO->countBids($userId, 1) >= 5) {
                $errors[] = "section limit reached";
        ***REMOVED***
    ***REMOVED***

        // Validation 7/7 "not enough e-dollar" student has not enough e-dollars to place the bid.
        // If it is an update of a previous bid for the same course, account for the e$ gained back
        // from the cancellation

        if ($existingBid) {
            $previousAmount = $existingBid['amount'];
            $difference = $amount - $previousAmount;
            //$bidDAO->refundbidamount($userId, $course);

            $userEDollar = $bidDAO->getEDollar($userId)['edollar'];
            if ($difference > $userEDollar) {
                $errors[] = "insufficient e$";
        ***REMOVED***
    ***REMOVED*** else {
            $userEDollar = $bidDAO->getEDollar($userId)['edollar'];
            if ($amount > $userEDollar) {
                $errors[] = "insufficient e$";
        ***REMOVED***
    ***REMOVED***

        // If still no errors
        if (!$errors) {
            if ($existingBid) {
                $bidDAO->refundbidamount($userId, $courseCode); // Drop prev bid first if exist.
        ***REMOVED***

            $bidDAO->addBid($userId, $courseCode, $section, $amount, $currentRound); // [EDIT: Added] Last param pls add round
    ***REMOVED***

        sort($errors); // Fucking important!!! Can only be placed here. Why? Read wiki.
***REMOVED***
    
    return $errors;
}


function deleteBid($userId, $course, $section)
{
    $roundDAO = new RoundDAO();
    $bidDAO = new BidDAO();

    if ($roundDAO->roundIsActive()) {
        if ($bidDAO->refundbidamount($userId, $$course, $section)) { } else {

            // "invalid course"	Course code does not exist in the system's records
            // "invalid userid"	userid does not exist in the system's records
            // "invalid section"	No such section ID exists for the particular course. Only check if course is valid
            // "round ended"	The current bidding round has already ended.
            // "no such bid"	No such bid exists in the system's records. Check only if there is an (1) active bidding round, and (2) course, userid and section are valid and (3)the round is currently active.
    ***REMOVED***
***REMOVED***
}
