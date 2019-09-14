***REMOVED***
    require_once '../includes/common.php';

    header("Content-Type: application/json");

    $errors = [
        isMissingOrEmpty('token'),
    ];

    $errors = array_filter($errors);

    if (!isEmpty($errors)) {
        $result = [
            "status" => "error",
            "messages" => array_values($errors)
        ];
***REMOVED***
    else {
        $token = $_GET['token'];

        if (verify_token($token)) {
            $studentDAO = new StudentDAO();
            $students = $studentDAO->retrieveAll();

            $courseDAO = new CourseDAO();
            $courses = $courseDAO->retrieveAll();

            $sectionDAO = new SectionDAO();
            $sections = $sectionDAO->retrieveAll();
            
            $result = [
                "status" => "success",
                "course" => $courses,
                "section" => $sections,
                "student" => $students,
            ];
    ***REMOVED***
        else {
            $errors = ["Unauthorised access."];
            $result = [
                "status" => "error",
                "messages" => array_values($errors)
            ];
    ***REMOVED***
***REMOVED***

    echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);