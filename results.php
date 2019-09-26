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
$bids = $bidDAO->retrieveResult($user['userid'], $currentRound['round']);


include 'includes/views/header.php';
?>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
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
                                        <th scope="col">Bid (e$)</th>
                                        <th scope="col">Course Code</th>
                                        <th scope="col">Section</th>
                                        <th scope="col">Result</th>
                                        <th scope="col">Round</th>
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
                                                <td><?php echo $bid['result']; ?></td>
                                                <td><?php echo $bid['round']; ?></td>
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