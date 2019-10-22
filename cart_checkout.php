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
        $sum += $amount;
***REMOVED***
    //Retrieve all value in "minVal[]" field
    if ($currentRound['round'] == 2) {
        for ($x = 0; $x < count($_POST['minVal']); $x++) {
            if ($_POST['amount'][$x] < $_POST['minVal'][$x]) {
                addError("Minimum Bid Value for {$_POST['course'][$x]} is e\${$_POST['minVal'][$x]}, your bid is e\${$_POST['amount'][$x]}");
        ***REMOVED***

    ***REMOVED***
***REMOVED***


    // Validation: Make sure I sum(amount[]) < my current edollar!!!!
    // if ($sum > $user['edollar']) {
    //     addError("You do not have enough edollar to place all your bids! Sum of all your bids: e\${$sum} vs. what you have: e\${$user['edollar']}.");
    // }

    // Validation: Make sure each bid is min. e$10.
    if (min($_POST['amount']) < 10) {
        addError("Minimum bid is e$10! You have entered a bid that is less than the minimum bid.");
***REMOVED***

    if (!isset($_SESSION['errors'])) {
        foreach ($bids as $bid) {
            $bidDAO->addBid($user['userid'], $bid['course'], $bid['section'], $bid['amount'], $currentRound['round']);
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
                            </tr>
                            </thead>
                            <tbody>
                            ***REMOVED***
                            $i = 0;
                            foreach ($bids as $bid) {
                                $cartItems = $bidDAO->retrieveCartItemsByCodeAndSection($user['userid'], $bid['course'], $bid['section'], $currentRound['round']);
                                $minBidVal = $bidDAO->getMinBidWithCourseCode($cartItems['course'], $cartItems['section']);
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
                                            $row = (int)$cartItems['size'] - (int)$row;
                                            echo $row;
                                    ***REMOVED***
                                        ?> <input type="hidden" id="minVal" name="minVal[]"
                                                  value="***REMOVED*** echo $minBidVal ?>"></td>
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