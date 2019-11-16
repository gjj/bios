<?php
require_once 'includes/common.php';

if (!isset($_SESSION['userid'])) {
    header("Location: .");
}

$viewData['title'] = "Courses";

$courseDAO = new CourseDAO();

if ($_POST) {
    if (isset($_POST['school'])) {
        $school = $_POST['school'];
    }

    if (isset($_POST['title'])) {
        $title = $_POST['title'];
    }
}

include 'includes/views/header.php';
?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Course Offerings</h1>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Module search</h5>
                    <div id="formStatus">
                        <i>
                            <?php
                            if ($_POST) {
                                if ($school or $title) {
                                    echo '<div class="alert alert-primary" role="alert">';
                                    if ($school) {
                                        if ($school == "all") {
                                            echo "Currently showing courses from <b>all</b> schools";
                                        } else {
                                            echo "Currently showing courses by school <b>{$school}</b>";
                                        }

                                        if ($title) {
                                            echo " and course title containing the text \"<b>{$title}</b>\".";
                                        }
                                    } else if ($title) {
                                        echo "Currently showing courses with course title containing the text \"<b>{$title}</b>\".";
                                    }
                                    echo '</div>';
                                }
                            }
                            ?>
                        </i>
                    </div>
                    <form id="form" method="post" action="">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="school">Search by school</label>
                                <select class="form-control" name="school">
                                    <option value="all">All schools</option>
                                    <?php
                                    $schoolsList = $courseDAO->retrieveAllSchools();

                                    foreach ($schoolsList as $schoolText) {
                                        echo "<option value=\"{$schoolText['school']}\">{$schoolText['school']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="title">Search by course title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Course title here!" />
                            </div>
                        </div>
                        <button type="submit" data-route="issue-cert" class="btn btn-success">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="pb-4"></div>


    <div class="row">
        <div class="col-md-12">
            <?php

            if (isset($school) and isset($title)) {
                $courses = $courseDAO->retrieveAll($school, $title);
            } else if (isset($school)) {
                $courses = $courseDAO->retrieveAll($school);
            } else if (isset($title)) {
                $courses = $courseDAO->retrieveAll("", $title);
            } else {
                $courses = $courseDAO->retrieveAll();
            }


            foreach ($courses as $course) {
                ?>
                <div class="row pb-5">
                    <div class="col-md-8">
                        <header>
                            <h5><a href="courses_view?course=<?php echo $course['course']; ?>"><?php echo $course['course'] . " " . $course['title']; ?></a></h5>
                            <span><?php echo $course['school']; ?></span>
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
                            <p><?php echo $course['exam date']; ?> <?php echo $course['exam start']; ?> - <?php echo $course['exam end']; ?></p>
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