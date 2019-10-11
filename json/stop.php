<?php
    require_once '../includes/common.php';

    header("Content-Type: application/json");

    $errors = [];
    // Remember to add token validation here...

    $roundDAO = new RoundDAO();
    $roundClearingDAO = new RoundClearingDAO();

    $currentRound = $roundDAO->getCurrentRound();

    if ($roundDAO->roundIsActive()) {
        $clearingResults = $roundClearingDAO->roundClearing($currentRound['round']);
        $roundDAO->stopRound();
    }
    else {
        $errors[] = "round already ended";
    }

    if (!$errors) {
        $result = [
            "status" => "success",
            "results(internal-use-only-will-remove-soon)" => $clearingResults
        ];
    }
    else {
        $result = [
            "status" => "error",
            "messages" => $errors
        ];
    }

    echo json_encode($result, JSON_PRETTY_PRINT);