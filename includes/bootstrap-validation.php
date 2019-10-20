<?php

    require_once 'common.php';

    // $data is an array of each row values
    function hasEmptyFields($data) {
        $result = [];

        for ($i = 0; $i <= count($data); $i++) {
            // Make sure that the key exists, isn't null or an empty string
            if (!isset($data[$i]) or $data[$i] == "") {
                $result[] = $i;
            }
        }

        return $result; // position of columns with missing values 
    }

    function commonValidation($data, $header) {
        $validationErrors = [];

        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i] == "") {
                $validationErrors[] = "blank $header[$i]";
            }
        }
        
        return $validationErrors;
    }
    
    // incomplete
    // function commonValidation($file){

    //     $counter = 0;
    //     $errors= [];
    //     while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {        
    //         $counter++;    
    //         $columnpos_arr = hasEmptyField($data);
    //         if (!empty($columnpos_arr)) {
    //             foreach($columnpos as $columnpos_arr){

    //             }
    //              $errors[] = "Empty field is in row: $counter cell $column_error";
    //         }


    //     }

        // echo $errors;


    // }
    $dataStudents = [];
    $dataCourses = [];
    $dataSections = [];
    $dataPrerequisites = [];
    $dataCourseCompleted = [];
    $dataBids = [];

    function validateStudent($data) {
        global $dataStudents;

        $userId = $data[0];
        $password = $data[1];
        $name = $data[2];
        $school = $data[3];
        $edollar = $data[4];

        $errors = [];

        if (strlen($userId) > 128) {
            $errors[] = "invalid userid"; 
        }
        if (array_key_exists($userId, $dataStudents)) {
            $errors[] = "duplicate userid";
        }
        if (is_numeric($edollar) == false or $edollar < 0.0 or $edollar != round($edollar, 2)) {
            $errors[] = "invalid e-dollar";
        }
        if (strlen($password) > 128) {
            $errors[] = "invalid password";
        }
        if (strlen($name) > 100) {
            $errors[] = "invalid name";
        }

        // Store in array for checking.
        if (!$errors) {
            $dataStudents[$userId] = [
                'school' => $school,
                'edollar' => $edollar
            ];
        }
        
        return $errors;
    }

    
    function validateCourse($data) {
        global $dataCourses;

        $course = $data[0];
        $school = $data[1];
        $title = $data[2];
        $description = $data[3];
        $examdate = $data[4];
        $examstart = $data[5];
        $examend = $data[6];

        $errors = [];
        
        if ($examdate != date("Ymd", strtotime($examdate))) {
            $errors[] = "invalid exam date";
        }
        if ($examstart != date("G:i", strtotime($examstart))) {
            $errors[] = "invalid exam start";
        }
        if ($examend != date("G:i", strtotime($examend)) or strtotime($examstart) > strtotime($examend)) {
            $errors[] = "invalid exam end";
        }
        if (strlen($title) > 100) {
            $errors[] = "invalid title";
        }
        if (strlen($description) > 1000) {
            $errors[] = "invalid description";
        }

        // Store in array for checking.        
        if (!$errors) {
            $dataCourses[$course] = [
                'school' => $school,
                'exam date' => $examdate,
                'exam start' => $examstart,
                'exam end' => $examend
            ];
        }

        return $errors;
    }
    
    function checkSectionFormat($section) {
        $section_num = "";
        for ($i = 0; $i < strlen($section); $i++) {
            // Skip the first character.
            if ($i != 0) {
                $section_num .= $section[$i];
            }
        }

        
        //$section_num = intval($section_num);

        if ($section_num < 1 or $section_num > 99 or !is_numeric($section_num)) {
            return false;
        }

        return true;
    }
    function validateSection($data) {
        global $dataCourses, $dataSections;

        $course = $data[0];
        $section = $data[1];
        $day = $data[2];
        $start = $data[3];
        $end = $data[4];
        $instructor = $data[5];
        $venue = $data[6];
        $size = $data[7];

        $errors = [];

        if (!array_key_exists($course, $dataCourses)) {
            $errors[] = "invalid course";
        }
        else {
            if ($section[0] != "S" or !checkSectionFormat($section)) {
                $errors[] = "invalid section";
            }
            if ($day < 1 or $day > 7) {
                $errors[] = "invalid day";
            }
            if ($start != date("G:i", strtotime($start))) {
                $errors[] = "invalid start";
            }
            if ($end != date("G:i", strtotime($end)) or strtotime($start) > strtotime($end)) {
                $errors[] = "invalid end";
            }
            if (strlen($instructor) > 100) {
                $errors[] = "invalid instructor";
            }
            if (strlen($venue) > 100) {
                $errors[] = "invalid venue";
            }
            if (!is_numeric($size) or $size < 1) {
                $errors[] = "invalid size";
            }
        }
        
        // Push to array for checks later.
        if (!$errors) {
            $dataSections[$course][$section] = [
                'day' => $day,
                'start' => $start,
                'end' => $end
            ];
            /*if (!array_key_exists($course, $dataSections)) {
                // If doesn't exist as a key yet...
                 // Create new key and array. Must be array, same reason as above.            }
            }
            else {
                $dataSections[$course][$section] = [
                    'day' => $day,
                    'start' => $start,
                    'end' => $end
                ]; // Push to existing array as each course can have multiple sections.
            }*/
        }


        return $errors;
    }

    function validatePrerequisite($data) {
        global $dataCourses, $dataPrerequisites;

        $course = $data[0];
        $prerequisite = $data[1];
        
        $errors = [];

        if (!array_key_exists($course, $dataCourses)) {
            $errors[] = "invalid course";
        }

        if (!array_key_exists($prerequisite, $dataCourses)) {
            $errors[] = "invalid prerequisite";
        }
        
        // Push to array for checks later.
        if (!$errors) {
            if (!array_key_exists($course, $dataPrerequisites)) {
                // If doesn't exist as a key yet...
                $dataPrerequisites[$course] = [$prerequisite]; // Create new key and array. Must be array, same reason as above.
            }
            else {
                $dataPrerequisites[$course][] = $prerequisite; // Push to existing array as each course can have multiple prerequisites.
            }
        }

        return $errors; 

    }
    
    function validateCourseCompletion($data) {
        global $dataStudents, $dataCourses, $dataPrerequisites, $dataCourseCompleted;
        
        $userId = $data[0];
        $course_completed = $data[1];

        $errors = [];

        if (!array_key_exists($userId, $dataStudents)) {
            $errors[] = "invalid userid";
        }

        if (!array_key_exists($course_completed, $dataCourses)) {
            $errors[] = "invalid course";
        }
        
        // If pass all basic validations...
        if (!$errors) {
            // Check if course even has prerequisites
            if (array_key_exists($course_completed, $dataPrerequisites)) {
                // Get my list of prerequisites for this course.
                $prerequisites = $dataPrerequisites[$course_completed]; // [IS102, IS103]
                
                // Check if I can find a current course completed list with the user ID. If empty or count() = 0 means user has not completed any course yet.
                if (isset($dataCourseCompleted[$userId]) and count($dataCourseCompleted[$userId])) {
                    $userCompletedCourses = $dataCourseCompleted[$userId];

                    $intersect = array_intersect($prerequisites, $userCompletedCourses);

                    if ($intersect == $prerequisites) {
                        $dataCourseCompleted[$userId][] = $course_completed; // Push to existing array as each user can complete multiple courses.
                    }
                    else {
                        $errors[] = "invalid course completed";
                    }
                }
                else {
                    $errors[] = "invalid course completed";
                }
            }
            else {
                // Course has no prerequisites!
                
                if (!array_key_exists($userId, $dataCourseCompleted)) {
                    // If doesn't exist as a key yet...
                    $dataCourseCompleted[$userId] = [$course_completed]; // Create new key and array. Must be array, same reason as above.

                }
                else {
                    $dataCourseCompleted[$userId][] = $course_completed; // Push to existing array as each user can complete multiple courses.
                }
            }
        }

        return $errors; 
    }
    
    function validateBid($data) {
        global $dataStudents, $dataCourses, $dataSections, $dataPrerequisites, $dataCourseCompleted, $dataBids;
        
        $userId = $data[0];
        $amount = $data[1];
        $course = $data[2];
        $section = $data[3];

        $bidDAO = new BidDAO;
        $userDAO = new UserDAO;
        $errors = [];

        if (!array_key_exists($userId, $dataStudents)) {
            $errors[] = "invalid userid";
        }
        if (!is_numeric($amount) or $amount < 10 or $amount != round($amount, 2)) {
            $errors[] = "invalid amount";
        }
        if (!array_key_exists($course, $dataCourses)) {
            $errors[] = "invalid course";
        }
        else {
            if (array_key_exists($course, $dataSections)) {
                $sections = $dataSections[$course];
                if (!array_key_exists($section, $sections)) {
                    $errors[] = "invalid section";
                }
            }
            else {
                $errors[] = "invalid section";
            }
        }

        // If no errors so far, then we proceed for our second round of validation checks...
        if (!$errors) {
            if (!$bidDAO->hasBiddedFor($userId, $course)) {
                // Validation 1/7 not own school course: This only happens in round 1 where students are allowed to bid for modules from their own school.
                if ($dataStudents[$userId]['school'] !== $dataCourses[$course]['school']) {
                    $errors[] = "not own school course";
                }

                // Validation 2/7 class timetable clash: The class timeslot for the section clashes with that of a previously bidded section.
                if ($bidDAO->checkTimetableConflicts($userId, [['course' => $course, 'section' => $section]], 1)) {
                $errors[] = "class timetable clash";
                }
                
                // Validation 3/7 exam timetable clash: The exam timeslot for this section clashes with that of a previously bidded section.
                if ($bidDAO->checkExamConflicts($userId, [['course' => $course, 'section' => $section]], 1)) {
                    $errors[] = "exam timetable clash";
                }

                // Validation 4/7 incomplete prerequisites:	student has not completed the prerequisites for this course.
                if (array_key_exists($course, $dataPrerequisites)) {
                    // Get my list of prerequisites for this course.
                    $prerequisites = $dataPrerequisites[$course];
                    
                    // Check if I can find a current course completed list with the user ID. If empty or count() = 0 means user has not completed any course yet.
                    if (isset($dataCourseCompleted[$userId]) and count($dataCourseCompleted[$userId])) {
                        $userCompletedCourses = $dataCourseCompleted[$userId];

                        $intersect = array_intersect($prerequisites, $userCompletedCourses);

                        if ($intersect != $prerequisites) {
                            $errors[] = "incomplete prerequisites";
                        }
                    }
                    else {
                        $errors[] = "incomplete prerequisites";
                    }
                }

                // Validation 5/7 course completed: student has already completed this course.
                if (array_key_exists($userId, $dataCourseCompleted)) {
                    if (in_array($course, $dataCourseCompleted[$userId])) {
                        $errors[] = "course completed";
                    }
                }
                
                // Validation 6/7 section limit reached: student has already bidded for 5 sections.
                if ($bidDAO->countBids($userId, 1) >= 5) {
                    $errors[] = "section limit reached";
                }
            }
            
            // Validation 7/7 "not enough e-dollar" student has not enough e-dollars to place the bid.
            // If it is an update of a previous bid for the same course, account for the e$ gained back
            // from the cancellation
            $existingBid = $bidDAO->getAmountIfBidExists($userId, $course);
            
            if ($existingBid) {
                //$previousAmount = $existingBid['amount'];
                $bidDAO->refundbidamount($userId, $course);
            }

            $userEDollar = $bidDAO->getEDollar($userId)['edollar'];
            if ($amount > $userEDollar) {
                $errors[] = "not enough e-dollar";
            }
        }
        
        /*$userSchool = $userDAO -> getSchoolbyID($userId);
        $checkOwnSchoolCourse = $bidDAO -> checkOwnSchoolCourse($userSchool, $course);
        // $checkTimetableConflicts = $bidDAO -> checkTimetableConflicts($userId, $courseSections, $round);
        // $checkExamConflicts = $bidDAO -> checkExamConflicts($userId, $courseSections, $round);
        $hasPrerequisites = $bidDAO -> hasPrerequisites($course);
        $hasCompletedCourse = $bidDAO ->hasCompletedCourse($userId, $course);
        $countBids = $bidDAO -> countBids($userId, $round);
        // $getEDollar = $bidDAO -> getEDollar($userId);

            if(!$checkOwnSchoolCourse){
                $error = "not own school course";
                $errors[] = $error;
            }
            // if(!$checkTimetableConflicts){
            //     $error = "class timetable clash";
            //     $errors[] = $error;
            // }
            // if(!$checkExamConflicts){
            //     $error = "exam timetable clash";
            //     $errors[] = $error;
            // }
            // if course has prerequisites, check if prerequisites were completed 
            if($hasPrerequisites){
                $completedPrerequisites = $bidDAO -> hasCompletedPrerequisites($userId, $course);
                // if student hasn't completed prerequisites, error out
                if($completedPrerequisites == False){
                    $error = "invalid course completed";
                    $errors[] = $error; 
                }
            }
            if($hasCompletedCourse){
                $error = "course completed";
                $errors[] = $error;
            }
            if($countBids > 5){
                $error = "section limit reached";
                $errors[] = $error;
            }
            // if($getEDollar)*/

        return $errors;


    }
    




?>