***REMOVED***
    require_once 'includes/common.php';
    
    $viewData['title'] = "Cart";

	if (!isLoggedIn()) {
		header("Location: .");
***REMOVED***

    $courseDAO = new CourseDAO();
	$roundDAO = new RoundDAO();
	$sectionDAO = new SectionDAO();
	$roundDAO = new RoundDAO();
    $bidDAO = new BidDAO();
    
	$currentRound = $roundDAO->getCurrentRound();
    $user = currentUser();
    

    if (!isEmpty($_GET['course']) and !isEmpty($_GET['section'])) {
        $code = $_GET['course'];
        $section = $_GET['section'];

        
        if ($bidDAO->refundbidamount($user['userid'], $code, $section)) {
            header("Location: cart?deleted=1");
    ***REMOVED***
        else {
            addError("Unable to drop bid for some reason.");
            header("Location: cart");
    ***REMOVED***
***REMOVED***
    else {
        addError("Unable to delete because no course code and section is provided.");
        header("Location: cart");
***REMOVED***
?>