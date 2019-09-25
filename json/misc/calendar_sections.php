***REMOVED***
    require_once '../../includes/common.php';

    header("Content-Type: application/json");

    if (!isLoggedIn()) {
		header("Location: ../../");
***REMOVED***

    $courseDAO = new CourseDAO();
	$roundDAO = new RoundDAO();
	$sectionDAO = new SectionDAO();
	$roundDAO = new RoundDAO();
    $bidDAO = new BidDAO();

    $userDAO = new UserDAO();

    $currentRound = $roundDAO->getCurrentRound();
    $user = currentUser();
    
    $bids = $bidDAO->getAllBidsForCalendar($user['userid']);
    
    $result = array();

    foreach ($bids as $bid) {
        $day = updateDayOfWeek($bid['day']);

        $item = array(
            "title" => $bid['course'] . " " .  $bid['title'],
            "start" => date("Y-m-d\T", strtotime($day . " this week")) . $bid['start'],
            "end" => date("Y-m-d\T", strtotime($day . " this week")) . $bid['end']
        );

        array_push($result, $item);
***REMOVED***

    echo json_encode($result, JSON_PRETTY_PRINT);

    function updateDayOfWeek($query) {
        $dayOfWeek = array(
            1 => "Monday",
            2 => "Tuesday",
            3 => "Wednesday",
            4 => "Thursday",
            5 => "Friday",
            6 => "Saturday",
            7 => "Sunday"
        );
        
        $query = $dayOfWeek[$query];

        return $query;
***REMOVED***