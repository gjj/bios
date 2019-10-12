***REMOVED***
	define("BIOS_STUDENT", true);
	
	require_once 'includes/common.php';

	if (!isset($_SESSION['userid'])) {
		header("Location: .");
	}

	$viewData['title'] = "Home";

	$user = currentUser();
	
	include 'includes/views/header.php';
?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 mt-3">
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
				$round = $roundDAO->getCurrentRound()['round'];

				if ($roundDAO->roundIsActive()) {
					$status = "Course Bidding Round " . $round . " is currently ongoing";
				}
				else {
					$status = "There are no active rounds currently.";
				}

				echo $status;
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