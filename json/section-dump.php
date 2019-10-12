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

            if (!$jsonError) {
                $errors = [
                    isMissingOrEmptyJson('course', $requestJson),
                    isMissingOrEmptyJson('section', $requestJson),
                ];

                $errors = array_filter($errors);
            }

            // If pass input validation...
            if (!$errors) {
                $course = $requestJson->course;
                $section = $requestJson->section;

                $bidDAO = new BidDAO();
                $bidsSuccessful = $bidDAO->retrieveAllSuccessfulBids(0, $course, $section);
            }
        }
        else {
            $errors[] = "invalid token";
        }
    }
    
    if (!$errors) {
        $result = [
            "status" => "success",
            "students" => $bidsSuccessful
        ];
    }
    else {
        $result = [
            "status" => "error",
            "messages" => array_values($errors)
        ];
    }

    echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION | JSON_NUMERIC_CHECK);