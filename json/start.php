***REMOVED***
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
    ***REMOVED***
        else {
            // If not active, and not round 1, means I start round 2!
            $roundDAO->startRound(2);
            $currentRound = 2;
    ***REMOVED***
***REMOVED***

    if (!$errors) {
        $result = [
            "status" => "success",
            "round" => $currentRound
        ];
***REMOVED***
    else {
        $result = [
            "status" => "error",
            "messages" => $errors
        ];
***REMOVED***

    echo json_encode($result, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);