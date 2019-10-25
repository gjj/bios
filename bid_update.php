<?php
    require_once 'includes/common.php';
    
    $viewData['title'] = "Update Bids";

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

    // Validation checks. If course and section param is not empty.
    if (!isEmpty($_GET['course']) and !isEmpty($_GET['section'])) {
        $course = $_GET['course'];
        $section = $_GET['section'];

        // Check if user has such a bid.
        $bid = $bidDAO->retrieveBidsByCodeAndSection($user['userid'], $course, $section, $currentRound['round']);

        // If I cannot find a courseCode/section pair, redirect back to cart page.
        if (!$bid) {
            header("Location: cart");
        }
    }
    else {
        header("Location: cart");
    }
    
    if ($_POST and isset($_POST['amount'])) {
        $amount = $_POST['amount'];

        if (!$roundDAO->roundIsActive()) {
            addError("Round is not active.");
        }

        if ($amount < 10 or !is_numeric($amount) or $amount != round($amount, 2)) {
            addError("Invalid amount. Minimum bid is e$10, and up to 2 decimal places only. [error: invalid amount]");
        }
        else {
            $difference = $amount - $bid['amount']; // NEW amount - OLD amount.

            // Validation: Check if I can afford the increment (i.e. increase my bid). If it's a decrement (i.e. lower my bid), confirm can.
            if ($difference > $user['edollar']) {
                addError("You do not have enough edollar to place all your bids! You need to topup e\${$difference} vs. what you have: e\${$user['edollar']}.");
            }

            if ($currentRound['round'] == 2) {
                // check min bid + vacancy maybe?
                $minBid = $bidDAO->getMinBid($bid['course'], $bid['section']);
    
                if ($minBid) $minBid = $minBid['bidAmount'];
                else $minBid = 10;
    
                if ($amount < $minBid) {
                    addError("This section (" . $bid['course'] . ", " . $bid['section'] . ") has a minimum bid of e$" . $minBid . " and you are bidding below it (e$" . $amount . ").");
                }
            }
        }

        // If there are NO errors from previous checks... then I update my bid.
        if (!isset($_SESSION['errors'])) {
            $bidDAO->updateBid($user['userid'], $bid['course'], $bid['section'], $amount, $currentRound['round']);
            
            //header("Location: cart");
        }
    }
    include 'includes/views/header.php';
?>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <div class="justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Update Bid</h1>
        </div>

        <div class="row pb-5">
			<div class="col-md-12">
                <h5>My Selected Section</h5>
                <h6> You currently have e$<?php echo $user['edollar']; ?> </h6>
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
                                <tr>
                                    <td>
                                        <input type="number" name="amount" class="form-control" step="0.01" min="10" value="<?php echo $bid['amount']; ?>" required />
                                    </td>
                                    <td><?php echo $bid['course'];?></td>
                                    <td><?php echo $bid['section'];?></td>
                                    <td><?php echo $bid['day'];?></td>
                                    <td><?php echo $bid['start'];?></td>
                                    <td><?php echo $bid['end'];?></td>
                                    <td><?php echo $bid['instructor'];?></td>
                                    <td><?php echo $bid['venue'];?></td>
                                    <td><?php echo $bid['size'];?></td>
                                </tr>
                            </tbody>
                        </table>
                        <p>
                            <button type="submit" class="btn btn-info">Bid</button>
                        </p>
                    </form>
				</section>
            </div>
        </div>
    </main>
<?php
	include 'includes/views/footer.php';
?>