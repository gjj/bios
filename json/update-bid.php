***REMOVED***
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
        
        $errors = [
            isMissingOrEmptyJson('amount', $json),
            isMissingOrEmptyJson('course', $json),
            isMissingOrEmptyJson('section', $json),
            isMissingOrEmptyJson('userid', $json),
        ];
            
        $errors = array_filter($errors);

        // If pass input validation...
        if (!$errors) {
            $userId = $json['userid'];
            $amount = $json['amount'];
            $course = $json['course'];
            $section = $json['section'];
            
            $errors = addOrUpdateBid($userId, $amount, $course, $section); // check errors
    ***REMOVED***
***REMOVED***
    
    if (!$errors) {
        $result = [
            "status" => "success"
        ];
***REMOVED***
    else {
        $result = [
            "status" => "error",
            "message" => array_values($errors)
        ];
***REMOVED***

    echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);