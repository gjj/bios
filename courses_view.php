<?php
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
                }
            }

            if ($currentRound['round'] == 2) {
                // round 2 must check vacancy left
            }

            $hasSuccessfulBid = $bidDAO->getSuccessfulBid($user['userid'], $courseCode);

            if ($hasSuccessfulBid) {
                addError("You're already enrolled in the course $courseCode.");
            }

            $hasPrerequisites = $bidDAO->hasPrerequisites($courseCode);
            $hasCompletedPrerequisites = $bidDAO->hasCompletedPrerequisites($user['userid'], $courseCode);

            if ($hasPrerequisites) {
                if (!$hasCompletedPrerequisites) {
                    addError("You have not completed the prerequisites. [error: incomplete prerequisites]");
                }
            }

            if ($bidDAO->hasCompletedCourse($user['userid'], $courseCode)) {
                addError("You've already completed the course. Why do you want to take again? [error: course completed]");
            }
        } else {
            addError("No active rounds currently.");
        }

        // If no errors until now... means passed all my previous validations!!
        if (empty($_SESSION['errors'])) {
            if ($sectionDAO->sectionExists($courseCode, $section)) {
                // Do further validation. Make sure POST-ed course and section code exists
                if (!$bidDAO->checkIfAddedToCart($user['userid'], $courseCode, $section, $currentRound['round'])) {
                    $bidDAO->addToCart($user['userid'], $courseCode, $section, $currentRound['round']);
                } else {
                    addError("Already added to cart!");
                }
            } else {
                addError("Course and section code pair does not exist. Nice try!");
            }
        }
    }

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
                <span class="course-code"><?php echo $course['course']; ?></span>
                <?php echo $course['title']; ?>
            </h1>
            <p class="pt-2"><?php echo $course['school']; ?></p>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row pb-5">
                    <div class="col-md-12">
                        <p class="pt-2">
                            <?php echo $course['description']; ?>
                        </p>

                        <?php
                        $prerequisites = $courseDAO->searchPrerequisites($course['course']);
                        $prerequisitesOf = $courseDAO->searchPrerequisitesOf($course['course']);

                        if ($prerequisites or $prerequisitesOf) {
                            ?>
                            <span><b>Prerequisite Tree</b></span>
                            <section>
                                <div class="container">
                                    <?php
                                    if ($prerequisitesOf) {
                                        ?>
                                        <!-- The current viewed course is a prerequisite of the following -->
                                        <ul class="tree">
                                            <?php
                                            foreach ($prerequisitesOf as $prerequisiteOf) {
                                                ?>
                                                <li class="branch prereqBranch">
                                                    <div class="node color-0 prereqNode">
                                                        <a href="courses_view?course=<?php echo $prerequisiteOf; ?>">
                                                            <?php echo $prerequisiteOf; ?>
                                                        </a>
                                                    </div>
                                                </li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                        <div class="node conditional">needs</div>

                                        <?php
                                    }
                                    ?>

                                    <!-- Root: Current course -->
                                    <ul class="prereqTree root">
                                        <li class="branch">
                                            <div class="node color-1">
                                                <b><?php echo $course['course']; ?></b>
                                            </div>


                                            <?php
                                            if ($prerequisites) {
                                                ?>
                                                <!-- Requires the following courses -->
                                                <ul class="prereqTree">
                                                    <li class="branch">
                                                        <div class="node conditional">all of</div>
                                                        <ul class="prereqTree">

                                                            <?php
                                                            foreach ($prerequisites as $prerequisite) {
                                                                ?>
                                                                <li class="branch">
                                                                    <div class="node">
                                                                        <a href="courses_view?course=<?php echo $prerequisite; ?>"><?php echo $prerequisite; ?></a>
                                                                    </div>
                                                                </li>
                                                                <?php
                                                            }
                                                            ?>
                                                        </ul>
                                                    </li>
                                                </ul>
                                                <?php
                                            }
                                            ?>
                                        </li>
                                    </ul>
                                </div>
                            </section>
                            </p>
                            <?php
                        }
                        ?>
                        <section>
                            <span><b>Exam</b></span>
                            <p><?php echo $course['exam date']; ?> <?php echo $course['exam start']; ?>
                                - <?php echo $course['exam end']; ?> • X hours</p>
                        </section>

                        <section>
                            <span><b>Sections Offered</b></span>

                            <?php
                            $sections = $sectionDAO->retrieveByCode($course['course']);

                            if ($sections) {
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
                                        <?php if ($currentRound['round'] == 2) { ?>
                                            <th scope="col">Min Bid</th>
                                        <?php } ?>
                                        <th scope="col"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    // Put here so that we only run 1x SQL query
                                    $courseCompleted = $bidDAO->hasCompletedCourse($user['userid'], $course['course']);


                                    $ownSchoolCourse = $bidDAO->checkOwnSchoolCourse($user['school'], $course['course']);
                                    $hasPrerequisites = $bidDAO->hasPrerequisites($course['course']);
                                    $hasCompletedPrerequisites = $bidDAO->hasCompletedPrerequisites($user['userid'], $course['course']);
                                    $hasSuccessfulBid = $bidDAO->getSuccessfulBid($user['userid'], $course['course']);

                                    foreach ($sections as $section) {
                                        ?>
                                        <tr>
                                            <th scope="row"><?php echo $section['section']; ?></th>
                                            <td><?php echo $section['day']; ?></td>
                                            <td><?php echo $section['start']; ?></td>
                                            <td><?php echo $section['end']; ?></td>
                                            <td><?php echo $section['instructor']; ?></td>
                                            <td><?php echo $section['venue']; ?></td>
                                            <td><?php
                                                if ($currentRound['round'] == 1) {
                                                    echo $section['size'];
                                                } elseif ($currentRound['round'] == 2) {
                                                    $row = $bidDAO->getSuccessfulByCourseCode($course['course'], $section['section']);
                                                    $row = (int)$section['size'] - (int)$row;
                                                    echo $row;
                                                }
                                                ?></td>
                                            <?php
                                            if ($currentRound['round'] == 2) {
                                                $minBid = $bidDAO->getMinBidWithCourseCode($course['course'], $section['section'])
                                                ?>
                                                <td>$<?php echo $minBid; ?></td>
                                                <?php
                                            }
                                            ?>
                                            <td>
                                                <form action="" method="post">
                                                    <input type="hidden" name="course"
                                                           value="<?php echo $course['course']; ?>"/>
                                                    <input type="hidden" name="section"
                                                           value="<?php echo $section['section']; ?>"/>
                                                    <?php

                                                    $error = null;

                                                    if (!$roundDAO->roundIsActive()) {
                                                        $error = "Not in round";
                                                    } else {
                                                        if ($hasSuccessfulBid) {
                                                            $error = "Already enrolled";
                                                        } else {
                                                            if ($bidDAO->checkIfAddedToCart($user['userid'], $course['course'], $section['section'], $currentRound['round'])) {
                                                                $error = 'Added to cart';
                                                            }

                                                            if ($courseCompleted) {
                                                                $error = 'Course completed';
                                                            }

                                                            if ($hasPrerequisites) {
                                                                if (!$hasCompletedPrerequisites) {
                                                                    $error = 'Prerequisite incomplete';
                                                                }
                                                            }

                                                            if ($currentRound['round'] == 1) {
                                                                if (!$ownSchoolCourse) {
                                                                    $error = 'Not own school course';
                                                                }
                                                            }
                                                            
                                                        }
                                                    }

                                                    if ($error) {
                                                        echo '<button class="btn btn-info" type="submit" disabled>' . $error . '</button>';
                                                    } else {
                                                        echo '<button class="btn btn-info" type="submit">Add to cart</button>';
                                                    }
                                                    ?>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <?php
                            }
                            ?>
                        </section>
                    </div>
                </div>
            </div>
            <div class="col-md-4">

            </div>
        </div>
    </main>

    <?php
}

include 'includes/views/footer.php';
?>