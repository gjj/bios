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
                    <div class="col-md-8">
                        <p class="pt-2">
                            <?php echo $course['description']; ?>
                        </p>

                        <?php
                            $prerequisites = $courseDAO->prerequisites($course['course']);

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
                            <p><?php echo $course['exam date']; ?> <?php echo $course['exam start']; ?> - <?php echo $course['exam end']; ?> • X hours</p>
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