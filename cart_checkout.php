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

    if ($_POST) {
        if ($_POST['checkout']) {
           $courseSections = array();

           foreach ($_POST['checkout'] as $rowNumber) {
                $courseSection = array(
                    'course' => $_POST['course'][$rowNumber],
                    'section' => $_POST['section'][$rowNumber]
                );

                array_push($courseSections, $courseSection);
       ***REMOVED***

           $_SESSION['courseSections'] = $courseSections;

           // Check duplicates. Logic validation 1/7.
           $selectedCourses = array_column($courseSections, 'course');

           $duplicates = array_unique(array_diff_assoc($selectedCourses, array_unique($selectedCourses)));

           if (count($duplicates)) {
               addError("You can only bid for one section per course! ");
               header("Location: cart");
       ***REMOVED***

           if ($bidDAO->checkTimetableConflicts($user['userid'], $courseSections, $currentRound['round'])) {
            addError("Timetable conflict! ");
            header("Location: cart");
       ***REMOVED***

           print_r($courseSections);

    ***REMOVED***
        else {
            header("Location: cart");
    ***REMOVED***
***REMOVED***
    else {
        header("Location: cart");
***REMOVED***

    include 'includes/views/header.php';
?>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <div class="justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Cart Checkout</h1>
        </div>

        <div class="row pb-5">
			<div class="col-md-12">
                <h5>My Selected Sections</h5>
                <h6> You currently have e$***REMOVED*** echo $user['edollar']; ?> </h6>
                <section>
                <form action="cart_checkout_process" method="post">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Bid (e$)</th>
                                    <th scope="col">Course Code</th>
                                    <th scope="col">Section</th>
                                    <th scope="col">Day</th>
                                    <th scope="col">Start</th>
                                    <th scope="col">End</th>
                                    <th scope="col">Instructor</th>
                                    <th scope="col">Venue</th>
                                    <th scope="col">Size</th>
                                </tr>
                            </thead>
                            <tbody>
                                ***REMOVED***
                                    $i = 0;
                                    foreach ($courseSections as $courseSection) {
                                        $bid = $bidDAO->retrieveCartItemsByCodeAndSection($user['userid'], $courseSection['course'], $courseSection['section'], $currentRound['round']);
                                ?>
                                <tr>
                                    <td>
                                        <input type="number" name="amount[]" class="form-control" />
                                    </td>
                                    <td>***REMOVED*** echo $bid['course'];?></td>
                                    <td>***REMOVED*** echo $bid['section'];?></td>
                                    <td>***REMOVED*** echo $bid['day'];?></td>
                                    <td>***REMOVED*** echo $bid['start'];?></td>
                                    <td>***REMOVED*** echo $bid['end'];?></td>
                                    <td>***REMOVED*** echo $bid['instructor'];?></td>
                                    <td>***REMOVED*** echo $bid['venue'];?></td>
                                    <td>***REMOVED*** echo $bid['size'];?></td>
                                </tr>
                                ***REMOVED***
                                        $i++;
                                ***REMOVED***
                                ?>
                            </tbody>
                        </table>
                        <p>
                            <button type="submit" class="btn btn-info">Checkout</button>
                        </p>
                    </form>
				</section>
            </div>
        </div>
    </main>
***REMOVED***
	include 'includes/views/footer.php';
?>