***REMOVED***
require_once 'includes/common.php';

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

if (!empty($_POST))
    if (!isEmpty($_POST['course']) and !isEmpty($_POST['section'])) {
        $courseCode = $_POST['course'];
        $section = $_POST['section'];

        if ($roundDAO->roundIsActive()) {
            if ($currentRound['round'] == 1) {
                if (!$bidDAO->checkOwnSchoolCourse($user['school'], $courseCode)) {
                    addError("You cannot bid for courses not offered by your school in Round 1. [error: not own school course]");
            ***REMOVED***
        ***REMOVED***

            if ($currentRound['round'] == 2) {
                // round 2 must check vacancy left
        ***REMOVED***

            $hasSuccessfulBid = $bidDAO->getSuccessfulBid($user['userid'], $courseCode);

            if ($hasSuccessfulBid) {
                addError("You're already enrolled in the course $courseCode.");
        ***REMOVED***

            $hasPrerequisites = $bidDAO->hasPrerequisites($courseCode);
            $hasCompletedPrerequisites = $bidDAO->hasCompletedPrerequisites($user['userid'], $courseCode);

            if ($hasPrerequisites) {
                if (!$hasCompletedPrerequisites) {
                    addError("You have not completed the prerequisites. [error: incomplete prerequisites]");
            ***REMOVED***
        ***REMOVED***

            if ($bidDAO->hasCompletedCourse($user['userid'], $courseCode)) {
                addError("You've already completed the course. Why do you want to take again? [error: course completed]");
        ***REMOVED***
    ***REMOVED*** else {
            addError("No active rounds currently.");
    ***REMOVED***

        // If no errors until now... means passed all my previous validations!!
        if (empty($_SESSION['errors'])) {
            if ($sectionDAO->sectionExists($courseCode, $section)) {
                // Do further validation. Make sure POST-ed course and section code exists
                if (!$bidDAO->checkIfAddedToCart($user['userid'], $courseCode, $section, $currentRound['round'])) {
                    $bidDAO->addToCart($user['userid'], $courseCode, $section, $currentRound['round']);
            ***REMOVED*** else {
                    addError("Already added to cart!");
            ***REMOVED***
        ***REMOVED*** else {
                addError("Course and section code pair does not exist. Nice try!");
        ***REMOVED***
    ***REMOVED***
***REMOVED***

if (isset($_GET['course'])) {
    $courseCode = $_GET['course'];

    $course = $courseDAO->retrieveByCode($courseCode);

    $viewData['title'] = $course['course'] . " " . $course['title'];
    $viewData['styles'] = "
			<style>
				.course-code {
					display: block;
					margin-bottom: .2rem;
					font-weight: 200;
					font-size: 2.4rem;
					/*color: #ff5138;*/
				}

				.container{
					display:flex;
					overflow-y:auto;
					align-items:center;
					padding:1rem 0
				}
				@media (min-width:576px){
					.container{
						justify-content:center
					}
				}
				.root .color-1{
					margin:0;
					font-size:1rem
				}
				.root .color-1:before{
					display:none
				}
				.tree,.prereqTree{
					position:relative;
					list-style:none;
					padding:0;
					margin:.125rem 0;
					text-align:center
				}
				.node{
					position:relative;
					display:flex;
					flex:0 1 5rem;
					justify-content:center;
					align-items:center;
					padding:.125rem .25rem;
					margin:0 0 1px .75rem;
					border-radius:.25rem
				}
				.node:before{
					top:50%;
					left:-.75rem;
					width:.75rem;
					height:1px
				}
				.link{
					display:block;
					width:100%
				}
				.link,.link:hover{
					color:currentColor
				}
				.branch:after,.branch:before,.conditional:after,.node:before{
					content:\"\";
					position:absolute;
					background:#d3d3d3;
				}
				.conditional{
					flex:0 0 auto;
					margin-right:.75rem;
					border:0
				}
				.conditional:after{
					top:50%;
					right:-.75rem;
					width:.75rem;
					height:1px
				}
				.branch{
					position:relative;
					display:flex;
					align-items:center
				}
				.branch:after,.branch:before{
					left:0;
					width:1px;
					height:50%
				}
				.branch:before{
					top:0
				}
				.branch:after{
					bottom:0
				}
				.branch:first-child:before,.branch:last-child:after{
					display:none
				}
				.prereqBranch:after,.prereqBranch:before{
					right:0;
					left:auto
				}
				.prereqNode{
					margin:0 .75rem 1px 0
				}
				.prereqNode:before{
					right:-.75rem;
					left:auto
				}

			</style>";

    include 'includes/views/header.php';
    ?>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <div class="justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <span class="course-code">***REMOVED*** echo $course['course']; ?></span>
                ***REMOVED*** echo $course['title']; ?>
            </h1>
            <p class="pt-2">***REMOVED*** echo $course['school']; ?></p>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row pb-5">
                    <div class="col-md-12">
                        <p class="pt-2">
                            ***REMOVED*** echo $course['description']; ?>
                        </p>

                        ***REMOVED***
                        $prerequisites = $courseDAO->searchPrerequisites($course['course']);
                        $prerequisitesOf = $courseDAO->searchPrerequisitesOf($course['course']);

                        if ($prerequisites or $prerequisitesOf) {
                            ?>
                            <span><b>Prerequisite Tree</b></span>
                            <section>
                                <div class="container">
                                    ***REMOVED***
                                    if ($prerequisitesOf) {
                                        ?>
                                        <!-- The current viewed course is a prerequisite of the following -->
                                        <ul class="tree">
                                            ***REMOVED***
                                            foreach ($prerequisitesOf as $prerequisiteOf) {
                                                ?>
                                                <li class="branch prereqBranch">
                                                    <div class="node color-0 prereqNode">
                                                        <a href="courses_view?course=***REMOVED*** echo $prerequisiteOf; ?>">
                                                            ***REMOVED*** echo $prerequisiteOf; ?>
                                                        </a>
                                                    </div>
                                                </li>
                                                ***REMOVED***
                                        ***REMOVED***
                                            ?>
                                        </ul>
                                        <div class="node conditional">needs</div>

                                        ***REMOVED***
                                ***REMOVED***
                                    ?>

                                    <!-- Root: Current course -->
                                    <ul class="prereqTree root">
                                        <li class="branch">
                                            <div class="node color-1">
                                                <b>***REMOVED*** echo $course['course']; ?></b>
                                            </div>


                                            ***REMOVED***
                                            if ($prerequisites) {
                                                ?>
                                                <!-- Requires the following courses -->
                                                <ul class="prereqTree">
                                                    <li class="branch">
                                                        <div class="node conditional">all of</div>
                                                        <ul class="prereqTree">

                                                            ***REMOVED***
                                                            foreach ($prerequisites as $prerequisite) {
                                                                ?>
                                                                <li class="branch">
                                                                    <div class="node">
                                                                        <a href="courses_view?course=***REMOVED*** echo $prerequisite; ?>">***REMOVED*** echo $prerequisite; ?></a>
                                                                    </div>
                                                                </li>
                                                                ***REMOVED***
                                                        ***REMOVED***
                                                            ?>
                                                        </ul>
                                                    </li>
                                                </ul>
                                                ***REMOVED***
                                        ***REMOVED***
                                            ?>
                                        </li>
                                    </ul>
                                </div>
                            </section>
                            </p>
                            ***REMOVED***
                    ***REMOVED***
                        ?>
                        <section>
                            <span><b>Exam</b></span>
                            <p>***REMOVED*** echo $course['exam date']; ?> ***REMOVED*** echo $course['exam start']; ?>
                                - ***REMOVED*** echo $course['exam end']; ?> • X hours</p>
                        </section>

                        <section>
                            <span><b>Sections Offered</b></span>

                            ***REMOVED***
                            $sections = $sectionDAO->retrieveByCode($course['course']);

                            if ($sections) {
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

                                <table class="table">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Section</th>
                                        <th scope="col">Day</th>
                                        <th scope="col">Start</th>
                                        <th scope="col">End</th>
                                        <th scope="col">Instructor</th>
                                        <th scope="col">Venue</th>
                                        <th scope="col">Vacancies</th>
                                        ***REMOVED*** if ($currentRound['round'] == 2) { ?>
                                            <th scope="col">Min Bid</th>
                                        ***REMOVED*** } ?>
                                        <th scope="col"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    ***REMOVED***
                                    // Put here so that we only run 1x SQL query
                                    $courseCompleted = $bidDAO->hasCompletedCourse($user['userid'], $course['course']);


                                    $ownSchoolCourse = $bidDAO->checkOwnSchoolCourse($user['school'], $course['course']);
                                    $hasPrerequisites = $bidDAO->hasPrerequisites($course['course']);
                                    $hasCompletedPrerequisites = $bidDAO->hasCompletedPrerequisites($user['userid'], $course['course']);
                                    $hasSuccessfulBid = $bidDAO->getSuccessfulBid($user['userid'], $course['course']);

                                    foreach ($sections as $section) {
                                        ?>
                                        <tr>
                                            <th scope="row">***REMOVED*** echo $section['section']; ?></th>
                                            <td>***REMOVED*** echo $section['day']; ?></td>
                                            <td>***REMOVED*** echo $section['start']; ?></td>
                                            <td>***REMOVED*** echo $section['end']; ?></td>
                                            <td>***REMOVED*** echo $section['instructor']; ?></td>
                                            <td>***REMOVED*** echo $section['venue']; ?></td>
                                            <td>***REMOVED***
                                                if ($currentRound['round'] == 1) {
                                                    echo $section['size'];
                                            ***REMOVED*** elseif ($currentRound['round'] == 2) {
                                                    $row = $bidDAO->getSuccessfulByCourseCode($course['course'], $section['section']);
                                                    $vacancy = (int)$section['size'] - (int)$row;
                                                    echo $vacancy;
                                            ***REMOVED***
                                                ?></td>
                                            ***REMOVED***
                                            if ($currentRound['round'] == 2) {
                                                // // More Vacancies than Bids
                                                if($row <= $section['size']) {
                                                    $minBid = 10;
                                            ***REMOVED***
                                                // More Bids than Vacancies
                                                // else{
                                                //     $minBid = $bidDAO->getMinBidWithCourseCode($course['course'], $section['section']);
                                                // }
                                                ?>
                                                <td>$***REMOVED*** echo $minBid; ?></td>
                                                ***REMOVED***
                                        ***REMOVED***
                                            ?>
                                            <td>
                                                <form action="" method="post">
                                                    <input type="hidden" name="course"
                                                           value="***REMOVED*** echo $course['course']; ?>"/>
                                                    <input type="hidden" name="section"
                                                           value="***REMOVED*** echo $section['section']; ?>"/>
                                                    ***REMOVED***

                                                    $error = null;

                                                    if (!$roundDAO->roundIsActive()) {
                                                        $error = "Not in round";
                                                ***REMOVED*** else {
                                                        if ($hasSuccessfulBid) {
                                                            $error = "Already enrolled";
                                                    ***REMOVED*** else {
                                                            if ($bidDAO->checkIfAddedToCart($user['userid'], $course['course'], $section['section'], $currentRound['round'])) {
                                                                $error = 'Added to cart';
                                                        ***REMOVED***

                                                            if ($courseCompleted) {
                                                                $error = 'Course completed';
                                                        ***REMOVED***

                                                            if ($hasPrerequisites) {
                                                                if (!$hasCompletedPrerequisites) {
                                                                    $error = 'Prerequisite incomplete';
                                                            ***REMOVED***
                                                        ***REMOVED***

                                                            if ($currentRound['round'] == 1) {
                                                                if (!$ownSchoolCourse) {
                                                                    $error = 'Not own school course';
                                                            ***REMOVED***
                                                        ***REMOVED***
                                                            
                                                    ***REMOVED***
                                                ***REMOVED***

                                                    if ($error) {
                                                        echo '<button class="btn btn-info" type="submit" disabled>' . $error . '</button>';
                                                ***REMOVED*** else {
                                                        echo '<button class="btn btn-info" type="submit">Add to cart</button>';
                                                ***REMOVED***
                                                    ?>
                                                </form>
                                            </td>
                                        </tr>
                                        ***REMOVED***
                                ***REMOVED***
                                    ?>
                                    </tbody>
                                </table>
                                ***REMOVED***
                        ***REMOVED***
                            ?>
                        </section>
                    </div>
                </div>
            </div>
            <div class="col-md-4">

            </div>
        </div>
    </main>

    ***REMOVED***
}

include 'includes/views/footer.php';
?>