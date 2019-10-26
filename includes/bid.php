<?php
require_once 'common.php';

function addOrUpdateBid($userId, $amount, $courseCode, $section, $round = 1)
{
    $bidDAO = new BidDAO();
    $roundDAO = new RoundDAO();
    $userDAO = new UserDAO();
    $courseDAO = new CourseDAO();
    $sectionDAO = new SectionDAO();
    $currentRound = $roundDAO->getCurrentRound()['round'];

    $errors = [];

    

    if (!$errors) {
        if (!is_numeric($amount) or $amount < 10 or $amount != round($amount, 2)) {
            $errors[] = "invalid amount";
        }

        if (!$courseDAO->retrieveByCode($courseCode)) {
            $errors[] = "invalid course";
        } elseif (!$sectionDAO->retrieveByCodeAndSection($courseCode, $section)) {
            $errors[] = "invalid section";
        }

        if (!$userDAO->retrieveById($userId)) {
            $errors[] = "invalid userid";
        }
    }

    // Not sure to place it here or not.
    // Round is active or not.
    if (!$roundDAO->roundIsActive()) {
        $errors[] = "round ended";
    }

    // If no errors so far, then we proceed for our second round of validation checks...
    if (!$errors) {
        $existingBid = $bidDAO->findExistingBid($userId, $courseCode);

        if (!$existingBid) {
            $user = $userDAO->retrieveById($userId);
            $course = $courseDAO->retrieveByCode($courseCode);

            if ($currentRound == 2) {
                // "bid too low" the amount must be more than the minimum bid (only applicable for round 2)
                // check min bid + vacancy maybe?
                $minBid = $bidDAO->getMinBid($courseCode, $section);
    
                if ($minBid) $minBid = $minBid['bidAmount'];
                else $minBid = 10;
    
                if ($amount < $minBid) {
                    $errors[] = "bid too low";
                }

                // course enrolled: Student has already won a bid for a section in this course in a previous round.
                if ($bidDAO->getSuccessfulBid($userId, $course, 1)) {
                    $errors[] = "course enrolled";
                }
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

        if ($existingBid) {
            $previousAmount = $existingBid['amount'];
            $newAmount = $amount - $previousAmount;
            //$bidDAO->refundbidamount($userId, $course);

            $userEDollar = $bidDAO->getEDollar($userId)['edollar'];
            if ($newAmount > $userEDollar) {
                $errors[] = "insufficient e$";
            }
        } else {
            $userEDollar = $bidDAO->getEDollar($userId)['edollar'];
            if ($amount > $userEDollar) {
                $errors[] = "insufficient e$";
            }
        }

        // If still no errors
        if (!$errors) {
            if ($existingBid) {
                $bidDAO->refundbidamount($userId, $courseCode); // Drop prev bid first.
            }

            $bidDAO->addBid($userId, $courseCode, $section, $amount); // Last param add round
        }
    }

    return $errors;
}


function deleteBid($userId, $course, $section)
{
    $roundDAO = new RoundDAO();
    $bidDAO = new BidDAO();

    if ($roundDAO->roundIsActive()) {
        if ($bidDAO->refundbidamount($userId, $code, $section)) { } else {

            // "invalid course"	Course code does not exist in the system's records
            // "invalid userid"	userid does not exist in the system's records
            // "invalid section"	No such section ID exists for the particular course. Only check if course is valid
            // "round ended"	The current bidding round has already ended.
            // "no such bid"	No such bid exists in the system's records. Check only if there is an (1) active bidding round, and (2) course, userid and section are valid and (3)the round is currently active.
        }
    }
}
