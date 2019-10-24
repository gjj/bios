***REMOVED***
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

if (isset($_SESSION['courseSections'])) {
    $bids = $_SESSION['courseSections'];
}
if ($_POST) {
    // Retrieve all my name="amount[]" fields.
    $sum = 0;
    for ($i = 0; $i < count($_POST['amount']); $i++) {
        $amount = $_POST['amount'][$i];
        $bids[$i]['amount'] = $amount;
        $bids[$i]['minBidVal'] = $_POST['minVal'][$i];
        $bids[$i]['course'] = $_POST['course'][$i];
        $bids[$i]['seatsLeft'] = $_POST['seats'][$i];
        $sum += $amount;
***REMOVED***


    // Validation: Make sure I sum(amount[]) < my current edollar!!!!
    if ($sum > $user['edollar']) {
        addError("You do not have enough edollar to place all your bids! Sum of all your bids: e\${$sum} vs. what you have: e\${$user['edollar']}.");
***REMOVED***

    // Validation: Make sure each bid is min. e$10.
    foreach ($bids as $bid) {
        $cartItems = $bidDAO->retrieveCartItemsByCodeAndSection($user['userid'], $bid['course'], $bid['section'], $currentRound['round']);
        $row = $bidDAO->getSuccessfulByCourseCode($bid['course'], $bid['section']);
        // More Vacancies than Bids
        $minBid = 10;
        if ($row < $cartItems['size']) {
            if (min($_POST['amount']) < $minBid) {
                addError("Minimum bid for {$bid['course']} is e$10! You have entered a bid that is less than the minimum bid.");
        ***REMOVED***
    ***REMOVED***
        if ($bid['minBidVal'] > $bid['amount'] and $currentRound['round'] == 2 and $bid['amount'] > $minBid) {

            addError("Minimum bid for {$bid['course']} is : e\${$bid['minBidVal']}. You have entered a bid of : e\${$bid['amount']} ");

    ***REMOVED***
***REMOVED***


    if (!isset($_SESSION['errors'])) {
        foreach ($bids as $bid) {
            // if ($currentRound['round'] == 1 && $bid['seatsLeft'] >= 1) {
            //     $bidDAO->addBid($user['userid'], $bid['course'], $bid['section'], $bid['amount'], $currentRound['round'], $bid['minBidVal']);
            // } else {
            //     // Round 2 or 0 vacancy for either Round 1 or 2
            //     $minBidUser = $bidDAO->getIdofMinBidUser($bid['course'], $bid['section'], $bid['minBidVal']);
            //     var_dump($minBidUser);
            //     $resultBid = $bidDAO->updateUserBid($minBidUser['user_id'], $bid['course'], $bid['section']);
            //     if ($resultBid == true) {
            //         $resultBid = false;
            //         //Execute Update new User's Bid
            //         $resultBid = $bidDAO->addBid($user['userid'], $bid['course'], $bid['section'], $bid['amount'], $currentRound['round']);
            //         if ($resultBid == true) {
            //             //Execute to refund User
            //             $bidDAO->refundBidUser($bid['minBidVal'], $minBidUser['user_id']);
            //     ***REMOVED***

            // ***REMOVED***

            // }
                // Round 2
                if($currentRound['round'] == 2) {
                    // Got vacancy, bidd is successful
                    if($bid['seatsLeft'] >=1) {
                        $bidDAO->addBid($user['userid'], $bid['course'], $bid['section'], $bid['amount'], $currentRound['round']);
                        $minBid = 10;
                ***REMOVED***
                    // Vacancy = 0, compare user's bid against all bids and store min bid
                    else{
                        // Let user submit bid first, then check if bid can stay as in, or else update to out
                        $currentbid = $bidDAO->addBid($user['userid'], $bid['course'], $bid['section'], $bid['amount'], $currentRound['round']);
                        // if min bid doesn't exist in min bid table yet, find min bid and insert into minbid table
                        if($bidDAO ->getMinBidfromMinBidTable($bid['course'], $bid['section']) == []){
                            $minBid = $bidDAO -> getMinBidWithCourseCode($bid['course'], $bid['section']);
                            $bidDAO -> insertMinBidforAllCourses($bid['course'], $bid['section'], $minBid, $user['userid']);
                    ***REMOVED***
                        // if min bid already exists in min bid table, compare current bid with min bid
                        else{
                            $existingMinBid = $bidDAO -> getMinBidfromMinBidTable($bid['course'], $bid['section']);
                            // if user's bid is lower than existing min bid, update user's bid to out
                            if($currentbid < $existingMinBid) {
                                $bidDAO -> updateUserBid($user['userid'], $bid['course'], $bid['section']);
                        ***REMOVED***
                            // if user's bid is equal to existing min bid, 
                            // elseif {

                            // }
                            // if user's bid is higher than existing min bid,
                            // 1) first retrieve user_id of existing min bid's owner
                            // 2) change existing min bid's owner's bid status from in to out
                            // 3) then update min bid in the table for that coursesection 
                            else {
                                $minBidDetails = $bidDAO -> getMinBidfromMinBidTable($bid['course'], $bid['section']);
                                foreach($minBidDetails as $minBidDetail) {
                                    $prevMinBidUserId = $minBidDetail['user_id'];
                                    $prevMinBidUserCourse = $minBidDetail['course'];
                                    $prevMinBidUserSection = $minBidDetail['section'];
                                    $bidDAO -> updateUserBid($prevMinBidUserId, $prevMinBidUserCourse, $prevMinBidUserSection);
                            ***REMOVED***
                                $minBid = $bid['amount'] + 1;
                                $bidDAO -> updateMinBidforAllCourses($bid['course'], $bid['section'], $minBid,$user['userid']);
                        ***REMOVED***
                    ***REMOVED***
    
                ***REMOVED***
                    $_SESSION['minBid'] = $minBid; 
            ***REMOVED***
    ***REMOVED***

        header("Location: cart");
***REMOVED***
}
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
            ***REMOVED***
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
                                <th scope="col">Min Bid</th>
                            </tr>
                            </thead>
                            <tbody>
                            ***REMOVED***
                            $i = 0;
                            foreach ($bids as $bid) {
                                $cartItems = $bidDAO->retrieveCartItemsByCodeAndSection($user['userid'], $bid['course'], $bid['section'], $currentRound['round']);
                                ?>
                                <tr>
                                    <td>
                                        <input type="number" name="amount[]" class="form-control"
                                               required/>
                                    </td>
                                    <td>***REMOVED*** echo $cartItems['course']; ?><input type="hidden" id="course"
                                                                                  name="course[]"
                                                                                  value="***REMOVED*** echo $cartItems['course'] ?>">
                                    </td>
                                    <td>***REMOVED*** echo $cartItems['section']; ?></td>
                                    <td>***REMOVED*** echo $cartItems['day']; ?></td>
                                    <td>***REMOVED*** echo $cartItems['start']; ?></td>
                                    <td>***REMOVED*** echo $cartItems['end']; ?></td>
                                    <td>***REMOVED*** echo $cartItems['instructor']; ?></td>
                                    <td>***REMOVED*** echo $cartItems['venue']; ?></td>
                                    <td>***REMOVED*** if ($currentRound['round'] == 1) {
                                            echo $cartItems['size'];
                                    ***REMOVED*** elseif ($currentRound['round'] == 2) {
                                            $row = $bidDAO->getSuccessfulByCourseCode($cartItems['course'], $cartItems['section']);
                                            $vacancy = (int)$cartItems['size'] - (int)$row;
                                            if($vacancy >= 0) {
                                                echo $vacancy;
                                        ***REMOVED***
                                            else {
                                                $vacancy = 0;
                                                echo $vacancy;
                                        ***REMOVED***
                                    ***REMOVED***
                                        ?><input type="hidden" id="seats" name="seats[]"
                                                 value="***REMOVED*** echo $vacancy ?>"></td>
                                    <td>$
                                        ***REMOVED***
                                        if ($currentRound['round'] == 2) {
                                            // // More Vacancies than Bids
                                            if ($vacancy < $cartItems['size']) {
                                                $minBid = 10;
                                                echo $minBid;
                                        ***REMOVED*** // More Bids than Vacancies
                                            else {
                                                // Need to retrieve minBid from minBid table, not minbid from all bids
                                                $minBidDetails = $bidDAO -> getMinBidfromMinBidTable($cartItems['course'], $cartItems['section']);
                                                foreach($minBidDetails as $minBidDetail) {
                                                    $minBid = floatval($minBidDetail['bidAmount']);
                                                    echo $minBid;
                                            ***REMOVED***
                                        ***REMOVED***
                                    ***REMOVED*** // For round 1, min bid is $10
                                        else {
                                            $minBid = 10;
                                            echo $minBid;
                                    ***REMOVED***
                                        // commented out cos it ERRORs out here!!!!
                                        // echo "<input type='hidden' id='minVal' name='minVal[]'
                                        // value=$minBid>";
                                        ?>
                                        
                                
                                        
                                    </td>
                                </tr>
                                ***REMOVED***
                                $i++;
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