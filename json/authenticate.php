***REMOVED***
    require_once '../includes/common.php';

    header("Content-Type: application/json");

    $errors = [
        isMissingOrEmpty('username'), 
        isMissingOrEmpty('password')
    ];


    $errors = array_filter($errors);

    if (!$errors) {
        $userId = $_POST['username'];
        $password = $_POST['password'];

        // Instantiate my User Data Access Object.
		$userDAO = new UserDAO();

        // UPDATE to match new messages: https://wiki.smu.edu.sg/is212/Project#Authenticate
		// Since we know that login(userId, pw) returns the record on success, or nothing on failure, we assign a variable to hold the results.

        if ($userDAO->retrieveById($userId)) {
            $login = $userDAO->login($userId, $password);

            // If can find a userId and password pair, means $login would have at least a value.
            if ($login) {
                $username = $login['userid'];
                $password = $login['password'];
                $role = $login['role'];
                
                if ($role == 1) {
                    // If admin, then I issue JWT token.
                    $token = generate_token($username);
            ***REMOVED***
                else {
                    $errors[] = "invalid username";
            ***REMOVED***
        ***REMOVED***
            else {
                $errors[] = "invalid password";
        ***REMOVED***
    ***REMOVED***
        else {
            $errors[] = "invalid username";
    ***REMOVED***
***REMOVED***

    if (!$errors) {
        $result = [
            "status" => "success",
            "token" => $token
        ];
***REMOVED***
    else {
        $result = [
            "status" => "error",
            "message" => array_values($errors)
        ];
***REMOVED***

    echo json_encode($result, JSON_PRETTY_PRINT);