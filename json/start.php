<?php
    require_once '../includes/common.php';

    header("Content-Type: application/json");

    $errors = [];
    // Remember to add token validation here...

    $roundDAO = new RoundDAO();
    $roundClearingDAO = new RoundClearingDAO();

    $currentRound = $roundDAO->getCurrentRound()['round'];

    if (!$roundDAO->roundIsActive()) {
        // If already round 2 and stopped (i.e. not active)...
        if ($currentRound == 2) {
            $errors[] = "round 2 ended";
        }
        else {
            // If not active, and not round 1, means I start round 2!
            $roundDAO->startRound(2);
            $currentRound = 2;
        }
    }

    if (!$errors) {
        $result = [
            "status" => "success",
            "round" => $currentRound
        ];
    }
    else {
        $result = [
            "status" => "error",
            "messages" => $errors
        ];
    }

    echo json_encode($result, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);