***REMOVED***
	require_once 'includes/common.php';

	if (!isset($_SESSION['userid'])) {
		header("Location: .");
	}

	$viewData['title'] = "Home";

	$user = currentUser();
	
	print_r($user);
?>

***REMOVED***
	include 'includes/views/header.php';
?>



<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
	<div class="jumbotron">
		<h2 class="display-4">
			Hello, ***REMOVED*** echo $user['name']; ?>!
		</h2>
		<p class="lead">
			You currently have <code>e$***REMOVED*** echo $user['edollar']; ?></code>.
		</p>
		<hr class="my-4">
		<p>
			***REMOVED***
				$roundDAO = new RoundDAO();
				$round = $roundDAO->getCurrentRound();

				if ($round['round'] == 1) {
					echo "Course Bidding Round 1 is currently ongoing.";
				}
				elseif ($round['round'] == 2) {
					echo "Course Bidding Round 2 is currently ongoing.";
				}
				else {
					echo "There are no active rounds currently.";
				}
			?>
		</p>
		<p class="lead">
			<a class="btn btn-primary btn-lg" href="courses" role="button">View course offerings</a>
		</p>
	</div>


</main>

***REMOVED***
	include 'includes/views/footer.php';
?>