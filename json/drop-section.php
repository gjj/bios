<?php
require_once '../includes/common.php';
require_once '../includes/bid.php';

header("Content-Type: application/json");

$errors = [
    isMissingOrEmpty('r'),
    isMissingOrEmpty('token'),
];

$errors = array_filter($errors);

if (!$errors) {
    $request = $_GET['r'];
    $token = $_GET['token'];

    $json = json_decode($request, true);

    $bidDAO = new BidDAO();
    $roundDAO = new RoundDAO();
    $userDAO = new UserDAO();
    $courseDAO = new CourseDAO();
    $sectionDAO = new SectionDAO();

    $errors = [
        isMissingOrEmptyJson('course', $json),
        isMissingOrEmptyJson('section', $json),
        isMissingOrEmptyJson('userid', $json)
    ];

    $errors = array_filter($errors);

    // If pass input validation...
    if (!$errors) {
        $userId = $json['userid'];
        $course = $json['course'];
        $section = $json['section'];

        if (!$courseDAO->retrieveByCode($course)) {
            $errors[] = "invalid course";
        } else {
            if (!$sectionDAO->retrieveByCodeAndSection($course, $section)) {
                $errors[] = "invalid section";
            }
        }

        if (!$userDAO->retrieveById($userId)) {
            $errors[] = "invalid userid";
        }

        if (!$roundDAO->roundIsActive()) {
            $errors[] = "round not active";
        }

    
        if (!$errors) {
            $refundSuccessful = $bidDAO->refundbidamount($userId, $course, $section);
        }
    }
}

if (!$errors) {
    $result = [
        "status" => "success",
    ];
} else {
    $result = [
        "status" => "error",
        "message" => array_values($errors)
    ];
}

echo json_encode($result, JSON_PRETTY_PRINT);
