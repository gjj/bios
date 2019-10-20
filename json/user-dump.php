***REMOVED***
    require_once '../includes/common.php';

    header("Content-Type: application/json");

    $errors = [
        isMissingOrEmpty('r'),
        isMissingOrEmpty('token'), // Check which layer token validation is at!!
    ];

    $errors = array_filter($errors);

    if (!$errors) {
        $request = $_GET['r'];
        $token = $_GET['token'];

        $json = json_decode($request, true);
        
        $errors = [
            isMissingOrEmptyJson('userid', $json),
        ];
        
        $errors = array_filter($errors);

        // If pass input validation...
        if (!$errors) {
            $userDAO = new UserDAO();
            $user = $userDAO->retrieveStudentById($json['userid']);

            if (!$user) {
                $errors = [
                    "invalid userid"
                ];

        ***REMOVED***
    ***REMOVED***
***REMOVED***

    if (!$errors) {
        $result = [
            "status" => "success",
        ];
        
        $result = array_merge($result, $user);
***REMOVED***
    else {
        $result = [
            "status" => "error",
            "message" => array_values($errors)
        ];
***REMOVED***

    echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION | JSON_NUMERIC_CHECK);