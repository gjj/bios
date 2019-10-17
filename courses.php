<?php
	require_once 'includes/common.php';

	if (!isset($_SESSION['userid'])) {
		header("Location: .");
	}

    $viewData['title'] = "Courses";
    
	include 'includes/views/header.php';
?>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Course Offerings</h1>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php
                    $courseDAO = new CourseDAO();
                    $courses = $courseDAO->retrieveAll();

                    foreach ($courses as $course) {
                ?>
                <div class="row pb-5">
                    <div class="col-md-8">
                        <header>
                            <h5><a href="courses_view?course=<?php echo $course['course']; ?>"><?php echo $course['course'] . " " . $course['title']; ?></a></h5>
                            <span><?php echo $course['school']; ?> • 1 Credit Unit</span>
                        </header>
                        
                        <p class="pt-2">
                            <?php echo $course['description']; ?>
                        </p>
                        
                        <!--<span><b>Sections Offered</b></span>
                        <p>
                            <?php
                                
                                echo $courseDAO->sectionsCount($course['course'])['sections_offered'];
                            ?>
                        </p>-->

                        <?php
                            $prerequisites = $courseDAO->searchPrerequisites($course['course']);

                            if ($prerequisites) {
                        ?>
                        <span><b>Prerequisite</b></span>
                        <p>
                            <?php
                                echo implode(", ", $prerequisites); 
                            ?>
                        </p>
                        <?php
                            }
                        ?>
                    </div>
                    <div class="col-md-4">
                        <section>
                            <span><b>Exam</b></span>
                            <p><?php echo $course['exam date']; ?> <?php echo $course['exam start']; ?> - <?php echo $course['exam end']; ?> • 3 hours</p>
                        </section>
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
            <div class="col-md-4">

            </div>
        </div>
    </main>

<?php
	include 'includes/views/footer.php';
?>