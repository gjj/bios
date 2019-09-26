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
    $cartItems = $bidDAO->retrieveAllCartItemsByUser($user['userid'], $currentRound['round']);
    $bids = $bidDAO->retrieveAllBidsByUser($user['userid'], $currentRound['round']);

    if ($_POST) {
        if (isset($_POST['checkoutForm'])) {
            $courseSections = array();

            foreach ($_POST['checkout'] as $rowNumber) {
                $courseSection = array(
                    'course' => $cartItems[$rowNumber]['course'],
                    'section' => $cartItems[$rowNumber]['section']
                );

                array_push($courseSections, $courseSection);
        ***REMOVED***
            
            // Check duplicates. Logic validation 1/7.
            $selectedCourses = array_column($courseSections, 'course');
            $duplicates = array_unique(array_diff_assoc($selectedCourses, array_unique($selectedCourses)));

            if (count($duplicates)) {
               addError("You can only bid for one section per course! ");
        ***REMOVED***

            if ($bidDAO->checkTimetableConflicts($user['userid'], $courseSections, $currentRound['round'])) {
                addError("Timetable conflict with either your bidded courses or your confirmed courses!");
        ***REMOVED***
           
            if ($bidDAO->checkExamConflicts($user['userid'], $courseSections, $currentRound['round'])) {
                addError("Exam conflict with either your bidded courses or your confirmed courses!");
        ***REMOVED***

            $count = count($_POST['checkout']);
            $countBiddedMods = $bidDAO->countBids($user['userid'], $currentRound['round']);
            
            if (($count + $countBiddedMods) > 5) {
                addError("You can only bid for up to 5 sections! You currently have {$countBiddedMods} bidded/confirmed courses, and you're bidding for {$count} more.");
        ***REMOVED***

            // If no errors, then we redirect to cart_checkout.php.
            if (!isset($_SESSION['errors'])) {
                $_SESSION['courseSections'] = $courseSections;
                header("Location: cart_checkout");
        ***REMOVED***
    ***REMOVED***

        else if (isset($_POST['updateForm'])) {
            $courseSections = array();
            
            foreach ($_POST['update'] as $rowNumber) {
                $courseSection = array(
                    'course' => $bids[$rowNumber]['course'],
                    'section' => $bids[$rowNumber]['section']
                );

                array_push($courseSections, $courseSection);
        ***REMOVED***
            // If no errors, then we redirect to cart_checkout.php.
            if (!isset($_SESSION['errors'])) {
                $_SESSION['courseSections'] = $courseSections;

                header("Location: cart_update");
        ***REMOVED***
    ***REMOVED***
***REMOVED***

    $viewData['styles'] = "
        <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css\" />
    ";
    $viewData['scripts'] = "
        <script src=\"https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js\"></script>
        <script src=\"https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js\"></script>
        <script>
            $(document).ready(function() {
                // page is now ready, initialize the calendar...
                $('#calendar').fullCalendar({
                    // put your options and callbacks here
                    defaultView: 'agendaWeek',
                    events: {
                        url: 'json/misc/calendar_sections',
                        type: 'GET',
                        textColor: 'white'
                ***REMOVED***,
                    error: function() {
                        alert('API error.');
                ***REMOVED***,
                    header: {
                        left: 'prev,next today myCustomButton',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                ***REMOVED***,
                    height: 'auto',
                    minTime: '08:00:00',
                    maxTime: '22:30:00'
            ***REMOVED***);
        ***REMOVED***);
        </script>
    ";
    
    include 'includes/views/header.php';
?>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <div class="justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Cart</h1>
        </div>

        <div class="row pb-5">
			<div class="col-md-12">
                <h5>My Cart</h5>

                            ***REMOVED***
								if (isset($_SESSION['errors'])) {
							?>
							<div class="alert alert-danger alert-dismissible fade show" role="alert">
								***REMOVED***
									printErrors();
								?>
								
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>

							***REMOVED***
								}
							?>

                <section>
                    <form action="" method="post">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Course Code</th>
                                    <th scope="col">Section</th>
                                    <th scope="col">Day</th>
                                    <th scope="col">Start</th>
                                    <th scope="col">End</th>
                                    <th scope="col">Instructor</th>
                                    <th scope="col">Venue</th>
                                    <th scope="col">Size</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                ***REMOVED***
                                    
                                    $i = 0;

                                    if ($cartItems) {
                                        foreach ($cartItems as $cartItem) {
                                        
                                ?>
                                <tr>
                                    <!-- This is just one way of passing data over, using hidden fields. Because our design is like that... -->
                                    <td>
                                        <input type="checkbox" name="checkout[]" value="***REMOVED*** echo $i; ?>" />
                                    </td>
                                    <td>***REMOVED*** echo $cartItem['course'];?></td>
                                    <td>***REMOVED*** echo $cartItem['section'];?></td>
                                    <td>***REMOVED*** echo $cartItem['day'];?></td>
                                    <td>***REMOVED*** echo $cartItem['start'];?></td>
                                    <td>***REMOVED*** echo $cartItem['end'];?></td>
                                    <td>***REMOVED*** echo $cartItem['instructor'];?></td>
                                    <td>***REMOVED*** echo $cartItem['venue'];?></td>
                                    <td>***REMOVED*** echo $cartItem['size'];?></td>
                                    <td><a href="cart_delete?course=***REMOVED*** echo $cartItem['course'];?>&section=***REMOVED*** echo $cartItem['section'];?>">Delete</a></td>
                                </tr>
                                ***REMOVED***
                                            $i++;
                                    ***REMOVED***
                                ***REMOVED***
                                    else {
                                ?>

                                <tr>
                                    <td colspan="10">No cart items currently. Why not <a href="courses">add some courses</a> to your cart?</td>
                                </tr>

                                ***REMOVED***
                                ***REMOVED***
                                ?>
                            </tbody>
                        </table>
                        <p>
                            <input type="submit" name="checkoutForm" class="btn btn-info"***REMOVED*** if (!$cartItems) echo " disabled"; ?> value="Checkout" />
                        </p>
                    </form>
				</section>
            </div>
        </div>

        <div class="row pb-5">
            <div class="col-md-12">
                <h5>My Bids</h5>
                <section>
                    <form action="" method="post">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Bid (e$)</th>
                                    <th scope="col">Course Code</th>
                                    <th scope="col">Section</th>
                                    <th scope="col">Day</th>
                                    <th scope="col">Start</th>
                                    <th scope="col">End</th>
                                    <th scope="col">Instructor</th>
                                    <th scope="col">Venue</th>
                                    <th scope="col">Size</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                    ***REMOVED***        
                                        $i = 0;                                
                                        if ($bids) {
                                            foreach ($bids as $bid) {
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="input" name="update[]" value="***REMOVED*** echo $i; ?>" />
                                        </td>
                                        <td>***REMOVED*** echo $bid['amount'];?></td>
                                        <td>***REMOVED*** echo $bid['course'];?></td>
                                        <td>***REMOVED*** echo $bid['section'];?></td>
                                        <td>***REMOVED*** echo $bid['day'];?></td>
                                        <td>***REMOVED*** echo $bid['start'];?></td>
                                        <td>***REMOVED*** echo $bid['end'];?></td>
                                        <td>***REMOVED*** echo $bid['instructor'];?></td>
                                        <td>***REMOVED*** echo $bid['venue'];?></td>
                                        <td>***REMOVED*** echo $bid['size'];?></td>
                                        <td><a href="bid_delete?course=***REMOVED*** echo $bid['course'];?>&section=***REMOVED*** echo $bid['section'];?>">Delete</a></td>
                                    </tr>
                                    ***REMOVED***
                                                $i++;
                                        ***REMOVED***
                                    ***REMOVED***
                                        else {
                                    ?>
                                    <tr>
                                        <td colspan="10">No bids currently.</td>
                                    </tr>
                                    ***REMOVED***
                                    ***REMOVED***
                                    ?>
                            </tbody>
                        </table>
                        <p>
                            <input type="submit" name="updateForm" class="btn btn-info"***REMOVED*** if (!$bids) echo " disabled"; ?> value="Update" />
                        </p>
                    </form>
                </section>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
            <h5>My Calendar</h5>
                <!-- For calendar only. -->
                <div id="calendar"></div>
            </div>
        </div>
    </main>
***REMOVED***
	include 'includes/views/footer.php';
?>