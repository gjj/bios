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
	
	if (!isEmpty($_POST['course']) and !isEmpty($_POST['section'])) {
		$courseCode = $_POST['course'];
		$section = $_POST['section'];

		// Do further validation. Make sure POST-ed course and section code exists
		if ($sectionDAO->sectionExists($courseCode, $section)) {
			if (!$bidDAO->checkIfAddedToCart($user['userid'], $courseCode, $section, $currentRound['round'])) {
				$bidDAO->addToCart($user['userid'], $courseCode, $section, $currentRound['round']);
			}
			else {
				addError("Already added to cart!");
			}
		}
		else {
			addError("Course and section code pair does not exist. Nice try!");
		}
	}

	if (isset($_GET['code'])) {
		$code = $_GET['code'];



		$course = $courseDAO->retrieveByCode($code);
	
	
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
			<p class="pt-2">***REMOVED*** echo $course['school']; ?> • 1 Credit Unit</p>
		</div>

		<div class="row">
			<div class="col-md-8">
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
												<a href="courses_view?code=***REMOVED*** echo $prerequisiteOf; ?>">
													***REMOVED*** echo $prerequisiteOf; ?>
												</a>
											</div>
										</li>
										***REMOVED***
											}
										?>
									</ul>
									<div class="node conditional">needs</div>

									***REMOVED***
										}
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
															<a href="courses_view?code=***REMOVED*** echo $prerequisite; ?>">***REMOVED*** echo $prerequisite; ?></a>
														</div>
													</li>
													***REMOVED***
														}
													?>
												  </ul>
											   </li>
											</ul>
											***REMOVED***
												}
											?>
										</li>
									</ul>
								</div>
							</section>
						</p>
						***REMOVED***
							}
						?>
						<section>
							<span><b>Exam</b></span>
							<p>***REMOVED*** echo $course['exam date']; ?> ***REMOVED*** echo $course['exam start']; ?> - ***REMOVED*** echo $course['exam end']; ?> • X hours</p>
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
							      		<th scope="col">Size</th>
										<th scope="col"></th>
							    	</tr>
								</thead>
							  	<tbody>
								  	***REMOVED***
										foreach ($sections as $section) {
									?>
							    	<tr>
							      		<th scope="row">***REMOVED*** echo $section['section']; ?></th>
							      		<td>***REMOVED*** echo $section['day']; ?></td>
							      		<td>***REMOVED*** echo $section['start']; ?></td>
							     		<td>***REMOVED*** echo $section['end']; ?></td>
							     		<td>***REMOVED*** echo $section['instructor']; ?></td>
							     		<td>***REMOVED*** echo $section['venue']; ?></td>
							     		<td>***REMOVED*** echo $section['size']; ?></td>
							     		<td>
											<form action="" method="post">
												<input type="hidden" name="course" value="***REMOVED*** echo $course['course']; ?>" />
												<input type="hidden" name="section" value="***REMOVED*** echo $section['section']; ?>" />
												<button class="btn btn-info" type="submit"***REMOVED*** if ($bidDAO->checkIfAddedToCart($user['userid'], $course['course'], $section['section'], $currentRound['round'])) echo "disabled"; ?>>Add to cart</button>
											</form>
										</td>
							   		</tr>
									***REMOVED***
										}
									?>
							  	</tbody>
							</table>
							***REMOVED***
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

***REMOVED***
	}

	include 'includes/views/footer.php';
?>