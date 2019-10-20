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

        $json = json_decode($request, true);
        $jsonError = json_last_error();
        
        $errors = [
            isMissingOrEmptyJson('course', $json),
            isMissingOrEmptyJson('section', $json),
        ];

        $errors = array_filter($errors);
            
        // If pass input validation...
        if (!$errors) {
            $course = $json['course'];
            $section = $json['section'];

            $bidDAO = new BidDAO();
            $bidsSuccessful = $bidDAO->retrieveAllSuccessfulBids(0, $course, $section);
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
            "message" => array_values($errors)
        ];
    }

    echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION | JSON_NUMERIC_CHECK);