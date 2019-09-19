<?php
    require_once 'includes/common.php';
    
    $viewData['title'] = "Cart";

	if (!isLoggedIn()) {
		header("Location: .");
    }

    if ($_POST) {
        if ($_POST['checkout']) {
           $courseSections = array();

           foreach ($_POST['checkout'] as $rowNumber) {
                $courseSection = array(
                    'code' => $_POST['code'][$rowNumber],
                    'section' => $_POST['section'][$rowNumber]
                );

                array_push($courseSections, $courseSection);
           }

           $_SESSION['courseSections'] = $courseSections;

           $selectedCourses = array_column($courseSections, 'code');

           $duplicates = array_unique(array_diff_assoc($selectedCourses, array_unique($selectedCourses)));

           if (count($duplicates)) {
               addError("You can only bid for one section per course!");
               header("Location: cart");
           }
        }
        else {
            header("Location: cart");
        }
    }
    else {
        header("Location: cart");
    }
    $courseDAO = new CourseDAO();
	$roundDAO = new RoundDAO();
	$sectionDAO = new SectionDAO();
	$roundDAO = new RoundDAO();
    $bidDAO = new BidDAO();
    
	$currentRound = $roundDAO->getCurrentRound();
    $user = currentUser();

    include 'includes/views/header.php';
?>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <div class="justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Cart Checkout</h1>
        </div>

        <div class="row pb-5">
			<div class="col-md-12">
                <h5>My Selected Sections</h5>
                <section>
                <form action="cart_checkout_process" method="post">
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
                                <?php
                                    $i = 0;
                                    foreach ($courseSections as $courseSection) {
                                        $bid = $bidDAO->retrieveCartItemsByCodeAndSection($user['userid'], $courseSection['code'], $courseSection['section'], $currentRound['round']);
                                ?>
                                <tr>
                                    <td>
                                        <input type="number" name="amount[]" class="form-control" />
                                    </td>
                                    <td><?php echo $bid['code'];?><input type="hidden" name="code[]" value="<?php echo $bid['code'];?>" /></td>
                                    <td><?php echo $bid['section'];?><input type="hidden" name="section[]" value="<?php echo $bid['section'];?>" /></td>
                                    <td><?php echo $bid['day'];?></td>
                                    <td><?php echo $bid['start'];?></td>
                                    <td><?php echo $bid['end'];?></td>
                                    <td><?php echo $bid['instructor'];?></td>
                                    <td><?php echo $bid['venue'];?></td>
                                    <td><?php echo $bid['size'];?></td>
                                </tr>
                                <?php
                                        $i++;
                                    }
                                ?>
                            </tbody>
                        </table>
                        <p>
                            <button type="submit" class="btn btn-info">Checkout</button>
                        </p>
                    </form>
				</section>
            </div>
        </div>
    </main>
<?php
	include 'includes/views/footer.php';
?>