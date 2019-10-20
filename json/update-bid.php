<?php
    require_once '../includes/common.php';

    header("Content-Type: application/json");

    $errors = [
        isMissingOrEmpty('r'),
        isMissingOrEmpty('token'),
    ];

    $errors = array_filter($errors);

    if (!$errors) {
        $request = $_GET['r'];
        $token = $_GET['token'];

        if (verify_token($token)) {
            $requestJson = json_decode($request);
            $jsonError = json_last_error();

            $bidDAO = new BidDAO();
            $roundDAO = new RoundDAO();
            $userDAO = new UserDAO();
            $courseDAO = new CourseDAO();
            $sectionDAO = new SectionDAO();

            if (!$jsonError) {
                $errors = [
                    isMissingOrEmptyJson('amount', $requestJson),
                    isMissingOrEmptyJson('course', $requestJson),
                    isMissingOrEmptyJson('section', $requestJson),
                    isMissingOrEmptyJson('userid', $requestJson),
                ];

                $errors = array_filter($errors);
            }

            // If pass input validation...
            if (!$errors) {
                $userId = $requestJson->userid;
                $amount = $requestJson->amount;
                $course = $requestJson->course;
                $section = $requestJson->section;

                if (!is_numeric($amount) or $amount < 10 or $amount != round($amount, 2)) {
                    $errors[] = "invalid amount";
                }
    
                if (!$courseDAO->retrieveByCode($course)) {
                    $errors[] = "invalid course";
                }
                else {
                    if (!$sectionDAO->retrieveByCodeAndSection($course, $section)) {
                        $errors[] = "invalid section";
                    }
                }
    
                if (!$userDAO->retrieveById($userId)) {
                    $errors[] = "invalid userid";
                }
        
                // If no errors so far, then we proceed for our second round of validation checks...
                if (!$errors) {
                    if (!$bidDAO->hasBiddedFor($userId, $course)) {
                        $user = $userDAO->retrieveById($userId);
                        $course = $courseDAO->retrieveByCode($course);
                        $currentRound = $roundDAO->getCurrentRound()['round'];

                        if ($currentRound == 2) {
                            // "bid too low" the amount must be more than the minimum bid (only applicable for round 2)
                        }

                        // Validation 1/7 not own school course: This only happens in round 1 where students are allowed to bid for modules from their own school.
                        if ($user['school'] !== $course['school']) {
                            $errors[] = "not own school course";
                        }
        
                        // Validation 2/7 class timetable clash: The class timeslot for the section clashes with that of a previously bidded section.
                        if ($bidDAO->checkTimetableConflicts($userId, [['course' => $course['course'], 'section' => $section]], $currentRound)) {
                            $errors[] = "class timetable clash";
                        }
                        
                        // Validation 3/7 exam timetable clash: The exam timeslot for this section clashes with that of a previously bidded section.
                        if ($bidDAO->checkExamConflicts($userId, [['course' => $course['course'], 'section' => $section]], $currentRound)) {
                            $errors[] = "exam timetable clash";
                        }
        
                        // Validation 4/7 incomplete prerequisites:	student has not completed the prerequisites for this course.
                        if ($bidDAO->hasPrerequisites($course['course'])) {                            
                            if (!$bidDAO->hasCompletedPrerequisites($userId, $course['course'])) {
                                $errors[] = "incomplete prerequisites";                                
                            }
                        }
        
                        // Validation 5/7 course completed: student has already completed this course.
                        if ($bidDAO->hasCompletedCourse($userId, $course['course'])) {
                            $errors[] = "course completed";
                        }

                        // course enrolled
                        
                        // Validation 6/7 section limit reached: student has already bidded for 5 sections.
                        if ($bidDAO->countBids($userId, 1) >= 5) {
                            $errors[] = "section limit reached";
                        }
                    }
                    
                    // Validation 7/7 "not enough e-dollar" student has not enough e-dollars to place the bid.
                    // If it is an update of a previous bid for the same course, account for the e$ gained back
                    // from the cancellation
                    $existingBid = $bidDAO->getAmountIfBidExists($userId, $course['course']);
                    
                    if ($existingBid) {
                        //$previousAmount = $existingBid['amount'];
                        $bidDAO->refundbidamount($userId, $course);
                    }
        
                    $userEDollar = $bidDAO->getEDollar($userId)['edollar'];
                    if ($amount > $userEDollar) {
                        $errors[] = "not enough e-dollar";
                    }
                }
            }
        }
        else {
            $errors[] = "invalid token";
        }
    }
    
    if (!$errors) {
        $result = [
            "status" => "success"
        ];
    }
    else {
        $result = [
            "status" => "error",
            "message" => array_values($errors)
        ];
    }

    echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);