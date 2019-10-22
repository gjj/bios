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
    }
    //Retrieve all value in "minVal[]" field
    // if ($currentRound['round'] == 2) {
    //     for ($x = 0; $x < count($_POST['minVal']); $x++) {
    //         if ($_POST['amount'][$x] < $_POST['minVal'][$x]) {
    //             addError("Minimum Bid Value for {$_POST['course'][$x]} is e\${$_POST['minVal'][$x]}, your bid is e\${$_POST['amount'][$x]}");
    //         }

    //     }
    // }


    // Validation: Make sure I sum(amount[]) < my current edollar!!!!
    if ($sum > $user['edollar']) {
        addError("You do not have enough edollar to place all your bids! Sum of all your bids: e\${$sum} vs. what you have: e\${$user['edollar']}.");
    }

    // Validation: Make sure each bid is min. e$10.
    foreach($bids as $bid){
        $cartItems = $bidDAO->retrieveCartItemsByCodeAndSection($user['userid'], $bid['course'], $bid['section'], $currentRound['round']);
        $row = $bidDAO->getSuccessfulByCourseCode($bid['course'], $bid['section']);
        // More Vacancies than Bids
        if($row <= $cartItems['size']) {
            $minBid = 10;
            if (min($_POST['amount']) < $minBid) {
                addError("Minimum bid is e$10! You have entered a bid that is less than the minimum bid.");
            }
        }
        // More Bids than Vacancies
        // else{
        //     $minBid = $bidDAO->getMinBidWithCourseCode($course['course'], $section['section']);
        //     addError("Minimum bid is $minBid! You have entered a bid that is less than the minimum bid.");
        // }
    }
    


    if (!isset($_SESSION['errors'])) {
        foreach ($bids as $bid) {
            $bidDAO->addBid($user['userid'], $bid['course'], $bid['section'], $bid['amount'], $currentRound['round']);
        }

        header("Location: cart");
    }
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
                                <th scope="col">Min Bid</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
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
                                    <td><?php echo $cartItems['course']; ?><input type="hidden" id="course"
                                                                                  name="course[]"
                                                                                  value="<?php echo $cartItems['course'] ?>">
                                    </td>
                                    <td><?php echo $cartItems['section']; ?></td>
                                    <td><?php echo $cartItems['day']; ?></td>
                                    <td><?php echo $cartItems['start']; ?></td>
                                    <td><?php echo $cartItems['end']; ?></td>
                                    <td><?php echo $cartItems['instructor']; ?></td>
                                    <td><?php echo $cartItems['venue']; ?></td>
                                    <td><?php if ($currentRound['round'] == 1) {
                                            echo $cartItems['size'];
                                        } elseif ($currentRound['round'] == 2) {
                                            $row = $bidDAO->getSuccessfulByCourseCode($cartItems['course'], $cartItems['section']);
                                            $row = (int)$cartItems['size'] - (int)$row;
                                            echo $row;
                                        }
                                        ?> <input type="hidden" id="minVal" name="minVal[]"
                                                  value="<?php echo $minBidVal ?>"></td>
                                    <td>
                                    <?php
                                    if ($currentRound['round'] == 2) {
                                        // // More Vacancies than Bids
                                            if($row <= $cartItems['size']) {
                                                $minBid = 10;
                                            }
                                            // More Bids than Vacancies
                                            // else{
                                            //     $minBid = $bidDAO->getMinBidWithCourseCode($course['course'], $section['section']);
                                            // }
                                        }
                                        // For round 1, min bid is $10 
                                    else{
                                        $minBid = 10;
                                    }
                                    echo $minBid;
                                    ?>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
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
<?php
include 'includes/views/footer.php';
?>