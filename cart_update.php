***REMOVED***
    require_once 'includes/common.php';
    
    $viewData['title'] = "Update Bids";

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

    if (isset($_SESSION['courseSections'])) {
        $bids = $_SESSION['courseSections'];
***REMOVED***
    
    if ($_POST) {
        // Retrieve all my name="amount[]" fields.
        $sum = 0;
        for ($i = 0; $i < count($_POST['amount']); $i++) {
            $amount = $_POST['amount'][$i];
            $bids[$i]['amount'] = $amount;
            print_r($bids);
            $sum += ($bids[$i]['old_amount'] - $amount); // Sum only the increment or decrement.
    ***REMOVED***

        // Validation: Make sure I sum(current bid - amount[]) < my current edollar!!!!
        if ($sum > $user['edollar']) {
            addError("You do not have enough edollar to place all your bids! Sum of all your bids: e\${$sum} vs. what you have: e\${$user['edollar']}.");
    ***REMOVED***
        else {
            foreach ($bids as $bid) {
                print_r($bidDAO->updateBid($user['userid'], $bid['course'], $bid['section'], $bid['amount'], $currentRound['round']));
        ***REMOVED***

            //header("Location: cart");
    ***REMOVED***
***REMOVED***
    include 'includes/views/header.php';
?>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <div class="justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Update Bids</h1>
        </div>

        <div class="row pb-5">
			<div class="col-md-12">
                <h5>My Selected Sections</h5>
                <h6> You currently have e$***REMOVED*** echo $user['edollar']; ?> </h6>
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
                                    for ($i = 0; $i < count($bids); $i++) {
                                        $bid = $bids[$i];
                                        $cartItems = $bidDAO->retrieveBidsByCodeAndSection($user['userid'], $bid['course'], $bid['section'], $currentRound['round']);
                                        $bids[$i]['old_amount'] = $cartItems['amount'];
                                ?>
                                <tr>
                                    <td>
                                        <input type="number" name="amount[]" class="form-control" step="0.01" value="***REMOVED*** echo $cartItems['amount']; ?>" required />
                                    </td>
                                    <td>***REMOVED*** echo $cartItems['course'];?></td>
                                    <td>***REMOVED*** echo $cartItems['section'];?></td>
                                    <td>***REMOVED*** echo $cartItems['day'];?></td>
                                    <td>***REMOVED*** echo $cartItems['start'];?></td>
                                    <td>***REMOVED*** echo $cartItems['end'];?></td>
                                    <td>***REMOVED*** echo $cartItems['instructor'];?></td>
                                    <td>***REMOVED*** echo $cartItems['venue'];?></td>
                                    <td>***REMOVED*** echo $cartItems['size'];?></td>
                                </tr>
                                ***REMOVED***
                                ***REMOVED***
                                ?>
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
***REMOVED***
	include 'includes/views/footer.php';
?>