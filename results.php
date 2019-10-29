***REMOVED***
require_once 'includes/common.php';

$viewData['title'] = "Results";

if (!isLoggedIn()) {
    header("Location: .");
}

$roundDAO = new RoundDAO();
$bidDAO = new BidDAO();

$currentRound = $roundDAO->getCurrentRound();
$user = currentUser();
if ($_POST) {
    if ($roundDAO->roundIsActive()) {
        if (isset($_POST['checkoutForm']) and isset($_POST['drop'])) {
            foreach ($_POST['drop'] as $drop) {
                $bidDAO->dropBid($drop, $user['userid']);
        ***REMOVED***
    ***REMOVED***
***REMOVED***
}
$bids = $bidDAO->retrieveResults($user['userid']);

include 'includes/views/header.php';
?>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Results</h1>
        </div>
        <div class="row pb-5">
            <div class="col-md-12">
                <h5>My Results</h5>
                <div class="row pb-5">
                    <div class="col-md-12">
                        <section>
                            <form action="" method="post">
                                <table class="table">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Bid (e$)</th>
                                        <th scope="col">Course Code</th>
                                        <th scope="col">Section</th>
                                        <th scope="col">Result</th>
                                        <th scope="col">Round</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    ***REMOVED***
                                    $i = 0;
                                    if ($bids) {
                                        foreach ($bids as $bid) {
                                            ?>
                                            <tr>
                                                <!-- This is just one way of passing data over, using hidden fields. Because our design is like that... -->
                                                <td>
                                                    <input class="dropCheck" type="checkbox" name="drop[]"
                                                           value="***REMOVED*** echo $bid['id']; ?>" ***REMOVED*** if ($bid['result'] == "out" and $bid['round'] == 1) {
                                                        echo "disabled";
                                                ***REMOVED*** ?>/>
                                                </td>
                                                <td>***REMOVED*** echo $bid['amount']; ?></td>
                                                <td>***REMOVED*** echo $bid['course']; ?></td>
                                                <td>***REMOVED*** echo $bid['section']; ?></td>
                                                <td>
                                                    ***REMOVED***
                                                    $result = $bid['result'];

                                                    if ($result == '-') echo 'Pending';
                                                    if ($result == 'in') echo 'Success';
                                                    if ($result == 'out') echo 'Fail';

                                                    ?>
                                                </td>
                                                <td>***REMOVED*** echo $bid['round']; ?></td>
                                            </tr>
                                            ***REMOVED***
                                            $i++;
                                    ***REMOVED***
                                ***REMOVED*** else {
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
                                    <input type="submit" name="checkoutForm" id="dropBid"
                                           class="btn btn-info" ***REMOVED*** if (!$bids or !$roundDAO->roundIsActive()) echo " disabled"; ?>
                                           value="Drop"/>
                                </p>
                            </form>
                        </section>
                    </div>
                </div>
    </main>
***REMOVED***
include 'includes/views/footer.php';
?>