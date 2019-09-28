***REMOVED***
    require_once '../includes/common.php';

    header("Content-Type: application/json");

    $roundClearingDAO = new RoundClearingDAO();
    $result = $roundClearingDAO->roundClearing(1);

    echo json_encode($result, JSON_NUMERIC_CHECK);