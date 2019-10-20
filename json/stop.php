<?php
    require_once '../includes/common.php';
    require_once '../includes/round.php';

    header("Content-Type: application/json");
    
    // Remember to add token validation here...

    $errors = [
        isMissingOrEmpty('token'),
    ];

    $errors = array_filter($errors);

    if (!$errors) {
        $token = $_GET['token'];

        if (verify_token($token)) {
            $json = doStop();
        }
        else {
            $errors[] = "invalid token";
        }
    }

    if (!$errors) {
        $result = $json;
    }
    else {
        $result = [
            "status" => "error",
            "message" => array_values($errors)
        ];
    }

    echo json_encode($result, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);