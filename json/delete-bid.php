***REMOVED***
    require_once '../includes/common.php';

    header("Content-Type: application/json");

    $errors = [
        isMissingOrEmpty('r'),
        isMissingOrEmpty('token'),
    ];

    $errors = array_filter($errors);

    if (!$errors) {
        $request = $_GET['r'];
        $token = $_GET['token'];

        if (verify_token($token)) {
            $requestJson = json_decode($request);
            $jsonError = json_last_error();

            $bidDAO = new BidDAO();
            $roundDAO = new RoundDAO();
            $userDAO = new UserDAO();
            $courseDAO = new CourseDAO();
            $sectionDAO = new SectionDAO();

            if (!$jsonError) {
                $errors = [
                    isMissingOrEmptyJson('course', $requestJson),
                    isMissingOrEmptyJson('section', $requestJson),
                    isMissingOrEmptyJson('userid', $requestJson)
                ];

                $errors = array_filter($errors);
        ***REMOVED***

            // If pass input validation...
            if (!$errors) {
                $userId = $requestJson->userid;
                $course = $requestJson->course;
                $section = $requestJson->section;

                if (!$courseDAO->retrieveByCode($course)){
                    $errors[] = "invalid course";
            ***REMOVED***
                else{
                    if(!$sectionDAO->retrieveByCodeAndSection($course, $section)) {
                        $errors[] = "invalid section";
                ***REMOVED***
            ***REMOVED***
                
                if (!$userDAO->retrieveById($userId)){
                    $errors[] = "invalid userid";
            ***REMOVED***

                if (!$roundDAO->roundIsActive()){
                    $errors[] = "round ended";
            ***REMOVED***

                $currentRound = $roundDAO->getCurrentRound()['round'];
                // if ($roundDAO->roundIsActive() and ($sectionDAO->retrieveByCodeAndSection($course, $section) and $userDAO->retrieveById($userId)){
                //     if ($bidDAO->retrieveBidsByCodeAndSection($userId, $course, $section, $currentRound)){

                // ***REMOVED***
                // }
                if (!$errors and !$bidDAO->retrieveBidsByCodeAndSection($userId, $course, $section, $currentRound)){
                    $errors[] = "no such bid";
            ***REMOVED***
                
                if (!$errors){
                    $refundSuccessful = $bidDAO->refundbidamount($userId, $course, $section);
            ***REMOVED***
                   
                
        ***REMOVED***
    ***REMOVED***
        else {
            $errors[] = "invalid token";
    ***REMOVED***
***REMOVED***
    
    if (!$errors) {
        $result = [
            "status" => "success",
        ];
***REMOVED***
    else {
        $result = [
            "status" => "error",
            "messages" => array_values($errors)
        ];
***REMOVED***

    echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION | JSON_NUMERIC_CHECK);