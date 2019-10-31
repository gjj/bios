***REMOVED***
    require_once 'includes/common.php';
    
    $viewData['title'] = "Drop Section";

	if (!isLoggedIn()) {
		header("Location: .");
***REMOVED***

    $courseDAO = new CourseDAO();
	$roundDAO = new RoundDAO();
	$sectionDAO = new SectionDAO();
	$roundDAO = new RoundDAO();
    $bidDAO = new BidDAO();
    $userDAO = new UserDAO();
    
	$currentRound = $roundDAO->getCurrentRound();
    $user = currentUser();
    

    if (!isEmpty($_GET['course']) and !isEmpty($_GET['section'])) {
        $course = $_GET['course'];
        $section = $_GET['section'];

        if (!$courseDAO->retrieveByCode($course)) {
            addError("invalid course");
    ***REMOVED*** else {
            if (!$sectionDAO->retrieveByCodeAndSection($course, $section)) {
                addError("invalid section");
        ***REMOVED***
    ***REMOVED***

        if (!$userDAO->retrieveById($user['userid'])) {
            addError("invalid userid");
    ***REMOVED***

        if (!$roundDAO->roundIsActive()) {
            addError("round not active");
    ***REMOVED***

        if (!isset($_SESSION['errors'])) {
            if ($bidDAO->refundbidamount($user['userid'], $course, $section)) {
                header("Location: results?deleted=1");
        ***REMOVED***
            else {
                addError("Unable to drop bid for some reason.");
        ***REMOVED***
    ***REMOVED***
        header("Location: results");

***REMOVED***
    else {
        addError("Unable to delete because no course code and section is provided.");
        header("Location: results");
***REMOVED***
?>