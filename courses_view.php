<?php
	require_once 'includes/common.php';

	if (!isset($_SESSION['userid'])) {
		header("Location: .");
	}

	if (isset($_GET['code'])) {
		$code = $_GET['code'];
		$courseDAO = new CourseDAO();
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
				<span class="course-code"><?php echo $course['course']; ?></span>
				<?php echo $course['title']; ?>
			</h1>
			<p class="pt-2"><?php echo $course['school']; ?> • 1 Credit Unit</p>
		</div>

		<div class="row">
			<div class="col-md-8">
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
												<a href="courses_view?code=<?php echo $prerequisiteOf; ?>">
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
															<a href="courses_view?code=<?php echo $prerequisite; ?>"><?php echo $prerequisite; ?></a>
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
							<p><?php echo $course['exam date']; ?> <?php echo $course['exam start']; ?> - <?php echo $course['exam end']; ?> • X hours</p>
						</section>

						<section>
							<span><b>Sections Offered</b></span>

							<?php
								$sectionDAO = new SectionDAO();
								$sections = $sectionDAO->retrieveByCode($course['course']);

								if ($sections) {
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
								  	<?php
										foreach ($sections as $section) {
									?>
							    	<tr>
							      		<th scope="row"><?php echo $section['section']; ?></th>
							      		<td><?php echo $section['day']; ?></td>
							      		<td><?php echo $section['start']; ?></td>
							     		<td><?php echo $section['end']; ?></td>
							     		<td><?php echo $section['instructor']; ?></td>
							     		<td><?php echo $section['venue']; ?></td>
							     		<td><?php echo $section['size']; ?></td>
							     		<td><button class="btn btn-info">Add to cart</button></td>
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