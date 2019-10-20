***REMOVED***
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
    ***REMOVED***
        else {
            $errors[] = "invalid token";
    ***REMOVED***
***REMOVED***

    if (!$errors) {
        $result = $json;
***REMOVED***
    else {
        $result = [
            "status" => "error",
            "message" => array_values($errors)
        ];
***REMOVED***

    echo json_encode($result, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);