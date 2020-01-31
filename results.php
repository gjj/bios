<?php
require_once 'includes/common.php';

$viewData['title'] = "Results";

if (!isLoggedIn()) {
    header("Location: .");
}

$roundDAO = new RoundDAO();
$bidDAO = new BidDAO();

$currentRound = $roundDAO->getCurrentRound();
$user = currentUser();
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
                        <?php
                            if (isset($_SESSION['errors'])) {
                                ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    You are unable to drop your section because:
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
                            <form action="" method="post">
                                <table class="table">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Bid (e$)</th>
                                        <th scope="col">Course Code</th>
                                        <th scope="col">Section</th>
                                        <th scope="col">Result</th>
                                        <th scope="col">Round</th>
                                        <th scope="col"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $i = 0;
                                    if ($bids) {
                                        foreach ($bids as $bid) {
                                            ?>
                                            <tr>
                                                <td><?php echo $bid['amount']; ?></td>
                                                <td><?php echo $bid['course']; ?></td>
                                                <td><?php echo $bid['section']; ?></td>
                                                <td>
                                                    <?php
                                                        $result = $bid['result'];

                                                        if ($result == '-') echo 'Pending';
                                                        if ($result == 'in') echo 'Success';
                                                        if ($result == 'out') echo 'Fail';

                                                    ?>
                                                </td>
                                                <td><?php echo $bid['round']; ?></td>
                                                <td>
                                                    <?php
                                                        if ($roundDAO->roundIsActive() and $result == 'in' and $bid['round'] == 1) {
                                                            echo '<a href="drop_section?course=' . $bid['course'] . '&section=' . $bid['section'] . '">Drop Section</a>';
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $i++;
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="10">No bids currently.</td>
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
    </main>
<?php
include 'includes/views/footer.php';
?>