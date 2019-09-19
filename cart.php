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
                        url: 'api/v1/calendar',
                        type: 'GET',
                        data: {
                            param: 1
                    ***REMOVED***,
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
                    <form action="cart_checkout" method="post">
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
                                </tr>
                            </thead>
                            <tbody>
                                ***REMOVED***
                                    $bids = $bidDAO->retrieveAllCartItemsByUser($user['userid'], $currentRound['round']);
                                    
                                    $i = 0;
                                    foreach ($bids as $bid) {
                                        
                                ?>
                                <tr>
                                    <!-- This is just one way of passing data over, using hidden fields. Because our design is like that... -->
                                    <td>
                                        <input type="checkbox" name="checkout[]" value="***REMOVED*** echo $i; ?>" />
                                    </td>
                                    <td>***REMOVED*** echo $bid['code'];?><input type="hidden" name="code[]" value="***REMOVED*** echo $bid['code'];?>" /></td>
                                    <td>***REMOVED*** echo $bid['section'];?><input type="hidden" name="section[]" value="***REMOVED*** echo $bid['section'];?>" /></td>
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

        <div class="row pb-5">
            <div class="col-md-12">
                <h5>My Bids</h5>
                <section>
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
							</tr>
						</thead>
						<tbody>
                                ***REMOVED***
                                    $bids = $bidDAO->retrieveAllBidsByUser($user['userid'], $currentRound['round']);
                                    
                                    foreach ($bids as $bid) {
                                ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="input" name="checkout[]" />
                                    </td>
                                    <td>***REMOVED*** echo $bid['amount'];?></td>
                                    <td>***REMOVED*** echo $bid['code'];?></td>
                                    <td>***REMOVED*** echo $bid['section'];?></td>
                                    <td>***REMOVED*** echo $bid['day'];?></td>
                                    <td>***REMOVED*** echo $bid['start'];?></td>
                                    <td>***REMOVED*** echo $bid['end'];?></td>
                                    <td>***REMOVED*** echo $bid['instructor'];?></td>
                                    <td>***REMOVED*** echo $bid['venue'];?></td>
                                    <td>***REMOVED*** echo $bid['size'];?></td>
                                </tr>
                                ***REMOVED***
                                ***REMOVED***
                                ?>
                        </tbody>
                    </table>
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