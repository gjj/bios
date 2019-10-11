***REMOVED***
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
***REMOVED***
    else {
        $errors[] = "round already ended";
***REMOVED***

    if (!$errors) {
        $result = [
            "status" => "success",
            "results(internal-use-only-will-remove-soon)" => $clearingResults
        ];
***REMOVED***
    else {
        $result = [
            "status" => "error",
            "messages" => $errors
        ];
***REMOVED***

    echo json_encode($result, JSON_PRETTY_PRINT);