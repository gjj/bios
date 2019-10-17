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
    $cartItems = $bidDAO->retrieveAllCartItemsByUser($user['userid'], $currentRound['round']);
    $bids = $bidDAO->retrieveAllBidsByUser($user['userid'], $currentRound['round']);

    if ($_POST) {
        if ($roundDAO->roundIsActive()) {
            if (isset($_POST['checkoutForm']) and isset($_POST['checkout'])) {
                $courseSections = array();
    
                foreach ($_POST['checkout'] as $rowNumber) {
                    $courseSection = array(
                        'course' => $cartItems[$rowNumber]['course'],
                        'section' => $cartItems[$rowNumber]['section']
                    );
    
                    array_push($courseSections, $courseSection);
                }
                
                if ($bidDAO->checkDuplicates($user['userid'], $courseSections, $currentRound['round'])) {
                    addError("You can only bid for one section per course!");
                }
    
                if ($bidDAO->checkTimetableConflicts($user['userid'], $courseSections, $currentRound['round'])) {
                    addError("Timetable conflict with either your bidded courses or your confirmed courses! [error: class timetable clash]");
                }
               
                if ($bidDAO->checkExamConflicts($user['userid'], $courseSections, $currentRound['round'])) {
                    addError("Exam conflict with either your bidded courses or your confirmed courses! [error: exam timetable clash]");
                }
    
                $count = count($_POST['checkout']);
                $countBiddedMods = $bidDAO->countBids($user['userid'], $currentRound['round']);
                
                if (($count + $countBiddedMods) > 5) {
                    addError("You can only bid for up to 5 sections! You currently have {$countBiddedMods} bidded/confirmed courses, and you're bidding for {$count} more. [error: section limit reached]");
                }
    
                // If no errors, then we redirect to cart_checkout.php.
                if (!isset($_SESSION['errors'])) {
                    $_SESSION['courseSections'] = $courseSections;
                    header("Location: cart_checkout");
                }
            }
        }
        else {
            addError("Round is not active.");
        }
    }

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
        <p class="lead">
			    You currently have <code>e$<?php echo $user['edollar']; ?></code>.
        </p>
        <div class="row pb-5">
			<div class="col-md-12">
                <h5>My Cart</h5>
                            <?php
								if (isset($_SESSION['errors'])) {
							?>
							<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<?php
									printErrors();
								?>
								
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>

							<?php
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
                                <?php
                                    
                                    $i = 0;

                                    if ($roundDAO->roundIsActive()) {
                                        if ($cartItems) {
                                            foreach ($cartItems as $cartItem) {
                                        
                                ?>
                                <tr>
                                    <!-- This is just one way of passing data over, using hidden fields. Because our design is like that... -->
                                    <td>
                                        <input type="checkbox" name="checkout[]" value="<?php echo $i; ?>" />
                                    </td>
                                    <td><?php echo $cartItem['course'];?></td>
                                    <td><?php echo $cartItem['section'];?></td>
                                    <td><?php echo $cartItem['day'];?></td>
                                    <td><?php echo $cartItem['start'];?></td>
                                    <td><?php echo $cartItem['end'];?></td>
                                    <td><?php echo $cartItem['instructor'];?></td>
                                    <td><?php echo $cartItem['venue'];?></td>
                                    <td><?php echo $cartItem['size'];?></td>
                                    <td><a href="cart_delete?course=<?php echo $cartItem['course'];?>&section=<?php echo $cartItem['section'];?>">Delete</a></td>
                                </tr>
                                <?php
                                                $i++;
                                            }
                                        }
                                        else {
                                ?>

                                <tr>
                                    <td colspan="10">No cart items currently. Why not <a href="courses">add some courses</a> to your cart?</td>
                                </tr>

                                <?php
                                        }
                                    }
                                    else {
                                ?>
                                <tr>
                                    <td colspan="10">Round is not active.</td>
                                </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <p>
                            <input type="submit" name="checkoutForm" class="btn btn-info"<?php if (!$cartItems or !$roundDAO->roundIsActive()) echo " disabled"; ?> value="Checkout" />
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
                                    <?php        
                                        $i = 0; 
                                        
                                        if ($roundDAO->roundIsActive()) {
                                            if ($bids) {
                                                foreach ($bids as $bid) {
                                    ?>
                                    <tr>
                                        <td><?php echo $bid['amount'];?></td>
                                        <td><?php echo $bid['course'];?></td>
                                        <td><?php echo $bid['section'];?></td>
                                        <td><?php echo $bid['day'];?></td>
                                        <td><?php echo $bid['start'];?></td>
                                        <td><?php echo $bid['end'];?></td>
                                        <td><?php echo $bid['instructor'];?></td>
                                        <td><?php echo $bid['venue'];?></td>
                                        <td><?php echo $bid['size'];?></td>
                                        <td>
                                            <a href="bid_update?course=<?php echo $bid['course'];?>&section=<?php echo $bid['section'];?>">Update</a> | 
                                            <a href="bid_delete?course=<?php echo $bid['course'];?>&section=<?php echo $bid['section'];?>">Delete</a>
                                        </td>
                                    </tr>
                                    <?php
                                                    $i++;
                                                }
                                            }
                                            else {
                                    ?>
                                    <tr>
                                        <td colspan="10">No bids currently.</td>
                                    </tr>
                                    <?php
                                            }
                                        }
                                        else {
                                    ?>
                                    <tr>
                                        <td colspan="10">Round is not active.</td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                            </tbody>
                        </table>
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
<?php
	include 'includes/views/footer.php';
?>