<?php
require_once '../includes/common.php';

header("Content-Type: application/json");
$errors = [
    isMissingOrEmpty('r'),
    isMissingOrEmpty('token'), // Check which layer token validation is at!!
];

$errors = array_filter($errors);

// If pass first layer of validations i.e. r and token exists
if (!$errors) {
    $request = $_GET['r'];
    $token = $_GET['token'];

    $json = json_decode($request, true);

    $errors = [
        isMissingOrEmptyJson('course', $json),
        isMissingOrEmptyJson('section', $json),
    ];

    $errors = array_filter($errors);

    // If pass common validation...
    if (!$errors) {
        $course = $json['course'];
        $section = $json['section'];

        $courseDAO = new CourseDAO();
        $sectionDAO = new SectionDAO();

        if (!$courseDAO->retrieveByCode($course)) {
            $errors[] = "invalid course";
        } else if (!$sectionDAO->retrieveByCodeAndSection($course, $section)) {
            $errors[] = "invalid section";
        }
    }

    // If pass input validation...
    if (!$errors) {
        $roundDAO = new RoundDAO();
        $bidDAO = new BidDAO();
        $currentRound = $roundDAO->getCurrentRound()['round'];

        $bids = $bidDAO->retrieveAllBidsBySection($course, $section);
        $size = $sectionDAO->retrieveSizeByCodeAndSection($course, $section);

        $size = $size[0]['size'];

        if (!$errors) {
            // During Round 1
            if ($currentRound == 1 and $roundDAO->roundIsActive()) {
                // Vacancy: the total available seats as all the bids are still pending.
                $vacancy = $size;

                $numBidsBySection = count($bids);

                // Minimum bid price: when #bid is less than the #vacancy, report the lowest bid amount.
                // Otherwise, set the price as the clearing price.
                // When there is no bid made, the minimum bid price will be 10.0 dollars.
                if ($numBidsBySection == 0) {
                    $minbid = 10.0;
                } elseif ($numBidsBySection < $vacancy) {
                    // Case of n = 0 has to be before this as min() function will throw an error if $bids is empty!
                    $minbid = min(array_column($bids, 'amount'));
                } elseif ($numBidsBySection >= $vacancy) {
                    $minbid = $bids[$vacancy - 1]['amount'];
                }
            }
            // After Round 1 Ended
            elseif ($currentRound == 1 and !$roundDAO->roundIsActive()) {
                // Vacancy: (the total available seats) - (number of successful bid during round 1).
                $numOfSuccessful = $bidDAO->getSuccessfulByCourseCode($course, $section, 1);
                $vacancy = $size - $numOfSuccessful;

                // Minimum bid price: report the lowest successful bid. If there was no bid made (or no successful bid) during round 1, the value will be 10.0 dollars.
                if ($numOfSuccessful == 0) {
                    $minbid = 10.0;
                } else {
                    $minbid = $bidDAO->getSuccessfulMinBidAmount($course, $section, 1);
                }
            }
            // During Round 2
            elseif ($currentRound == 2 and $roundDAO->roundIsActive()) {
                /*$round1 = $bidDAO->getSuccessfulByCourseCode($course, $section, 1);
                $round2 = $bidDAO->getSuccessfulByCourseCode($course, $section, 2);
                $vacancy = $size - ($round1 + $round2);*/

                $numOfSuccessful = $bidDAO->getSuccessfulByCourseCode($course, $section, 1);
                $vacancy = $size - $numOfSuccessful;

                if ($round2 >= $vacancy) {
                    // More Bids than Vacancies
                    $minbid = $bidDAO->getMinBid($course, $section)['bidAmount'];
                } else {
                    $minbid = 10.0;
                }
            }

            // After Round 2 Ended
            else {
                // Vacancy: (the total available seats) - (number of successfully enrolled students in round 1 and 2).
                $round1 = $bidDAO->getSuccessfulByCourseCode($course, $section, 1);
                $round2 = $bidDAO->getSuccessfulByCourseCode($course, $section, 2);
                $vacancy = $size - ($round1 + $round2);

                if ($round2 > 0) {
                    $minbid = $bidDAO->getMinBid($course, $section)['bidAmount'];
                } else {
                    $minbid = 10.0;
                }
            }

            $reports = $bidDAO->retrieveBidsReport($course, $section);
        }
    }
}
if (!$errors) {
    $result = [
        "status" => "success",
        "vacancy" => $vacancy,
        "min-bid-amount" => $minbid,
        "students " => $reports
    ];
} else {
    $result = [
        "status" => "error",
        "message" => array_values($errors)
    ];
}
echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);
