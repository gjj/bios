<?php
    require_once 'includes/common.php';
    
    $viewData['title'] = "Cart";

	if (!isLoggedIn()) {
		header("Location: .");
    }
    
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
                        },
                        textColor: 'white'
                    },
                    error: function() {
                        alert('API error.');
                    },
                    header: {
                        left: 'prev,next today myCustomButton',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    height: 'auto',
                    minTime: '08:00:00',
                    maxTime: '22:30:00'
                });
            });
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
                <section>
					<table class="table">
						<thead class="thead-dark">
							<tr>
							    <th scope="col">Checkout</th>
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
                            <?php
                                $bids = $bidDAO->retrieveAllBidsByUser($user['userid'], $currentRound['round']);
                                
                                foreach ($bids as $bid) {
                            ?>
							<tr>
                                <td>
                                    <input type="checkbox" name="checkout[]" />
                                </td>
                                <td><?php echo $bid['code'];?></td>
							    <td><?php echo $bid['section'];?></td>
							    <td><?php echo $bid['day'];?></td>
							    <td><?php echo $bid['start'];?></td>
							    <td><?php echo $bid['end'];?></td>
							    <td><?php echo $bid['instructor'];?></td>
							    <td><?php echo $bid['venue'];?></td>
							    <td><?php echo $bid['size'];?></td>
							</tr>
                            <?php
                                }
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
<?php
	include 'includes/views/footer.php';
?>