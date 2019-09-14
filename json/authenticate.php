***REMOVED***
    require_once '../includes/common.php';

    header("Content-Type: application/json");

    $errors = [
        isMissingOrEmpty('userId'), 
        isMissingOrEmpty('password')
    ];

    $errors = array_filter($errors);

    if (!isEmpty($errors)) {
        $result = [
            "status" => "error",
            "messages" => array_values($errors)
        ];
***REMOVED***
    else {
        $userId = $_POST['userId'];
        $password = $_POST['password'];

        // Instantiate my User Data Access Object.
		$userDAO = new UserDAO();

		// Since we know that login(userId, pw) returns the record on success, or nothing on failure, we assign a variable to hold the results.
        $login = $userDAO->login($userId, $password);
        
        // If can find a userId and password pair, means $login would have at least a value.
        if ($login) {
            $username = $login['userid'];
			$password = $login['password'];
            $role = $login['role'];
            
            if ($role == 1) {
                // If admin, then I issue JWT token.
                $token = generate_token($username);

                $result = [
                    "status" => "success",
                    "messages" => $token
                ];
        ***REMOVED***
            else {
                $result = [
                    "status" => "error",
                    "messages" => "Invalid user ID or password, not telling you which!" // Even though I can tell you that you do not have access, but I shouldn't.
                ];
        ***REMOVED***
    ***REMOVED***
        else {
            $result = [
                "status" => "error",
                "messages" => "Invalid user ID or password, not telling you which!"
            ];
    ***REMOVED***
***REMOVED***

    echo json_encode($result, JSON_PRETTY_PRINT);