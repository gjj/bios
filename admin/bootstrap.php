***REMOVED***
require_once '../includes/common.php';
require_once 'bootstrap-validation.php';

//header('Content-Type: application/json');

function doBootstrap()
{

    $errors = array();
    # need tmp_name -a temporary name create for the file and stored inside apache temporary folder- for proper read address
    $zip_file = $_FILES["bootstrap-file"]["tmp_name"];

    # Get temp dir on system for uploading
    $temp_dir = sys_get_temp_dir();

    # keep track of number of lines successfully processed for each file
    $students_processed = 0;
    $bids_processed = 0;
    $courses_processed = 0;
    $courses_completed_processed = 0;
    $prerequisites_processed = 0;
    $sections_processed = 0;

    # check file size
    if ($_FILES["bootstrap-file"]["size"] <= 0) {
        $errors[] = "input files not found";
***REMOVED***
    else {
        $zip = new ZipArchive;
        $res = $zip->open($zip_file);

        if ($res === TRUE) {
            $zip->extractTo($temp_dir);
            $zip->close();

            $students_path = "$temp_dir/student.csv";
            $courses_path = "$temp_dir/course.csv";
            $sections_path = "$temp_dir/section.csv";
            $prerequisites_path = "$temp_dir/prerequisite.csv";
            $courses_completed_path = "$temp_dir/course_completed.csv";
            $bids_path = "$temp_dir/bid.csv";

            $students = @fopen($students_path, "r");
            $courses = @fopen($courses_path, "r");
            $sections = @fopen($sections_path, "r");
            $prerequisites = @fopen($prerequisites_path, "r");
            $courses_completed = @fopen($courses_completed_path, "r");
            $bids = @fopen($bids_path, "r");

            if (empty($students) or empty($courses) or empty($sections) or empty($courses_completed) or empty($prerequisites) or empty($bids)) {
                $errors[] = "input files not found";

                if (!empty($students)) {
                    fclose($students);
                    @unlink($students_path);
            ***REMOVED***

                if (!empty($bids)) {
                    fclose($bids);
                    @unlink($bids_path);
            ***REMOVED***

                if (!empty($courses)) {
                    fclose($courses);
                    @unlink($courses_path);
            ***REMOVED***

                if (!empty($courses_completed)) {
                    fclose($courses_completed);
                    @unlink($courses_completed_path);
            ***REMOVED***

                if (!empty($prerequisites)) {
                    fclose($prerequisites);
                    @unlink($prerequisites_path);
            ***REMOVED***

                if (!empty($sections)) {
                    fclose($sections);
                    @unlink($sections_path);
            ***REMOVED***
        ***REMOVED***
            else {
                $connMgr = new ConnectionManager();
                $conn = $connMgr->getConnection();

                //Create DAO Objects
                $userDAO = new UserDAO();
                $bidDAO = new BidDAO();
                $courseDAO = new CourseDAO();
                $roundDAO = new RoundDAO();
                $sectionDAO = new SectionDAO();

                // Truncate all tables
                $userDAO->truncateAllTable();

                // Begin importing student.csv.
                $header = fgetcsv($students); // Skip first row.
                $student_row = 2;
                while (($data = fgetcsv($students)) !== false) {
                    $data = array_map('trim', $data);

                    $validationErrors = [];

                    $validationErrors = commonValidation($data, $header);

                    // If common validation fails, we don't even proceed with file-specific validation
                    if (!$validationErrors) {
                        $validationErrors = validateStudent($data);
                ***REMOVED***

                    if ($validationErrors) {
                        // If there are validation errors.
                        $errors[] = [
                            'file' => 'student.csv',
                            'line' => $student_row,
                            'message' => $validationErrors
                        ];
                ***REMOVED***
                    else {
                        $studentObj = new User($data[0], $data[1], $data[2], $data[3], $data[4]);
                        $userDAO->add($studentObj);
                        $students_processed++;
                ***REMOVED***

                    $student_row++;
            ***REMOVED***
                
                // Begin importing course.csv.
                $data = fgetcsv($courses); // Skip first row.
                $course_row = 2;
                while (($data = fgetcsv($courses)) !== false) {
                    $data = array_map('trim', $data);

                    $validationErrors = [];

                    $validationErrors = commonValidation($data, $header);

                    // If common validation fails, we don't even proceed with file-specific validation
                    if (!$validationErrors) {
                        $validationErrors = validateCourse($data);
                ***REMOVED***

                    if ($validationErrors) {
                        // If errors exist.
                        $errors[] = [
                            'file' => 'course.csv',
                            'line' => $course_row,
                            'message' => $validationErrors
                        ];
                ***REMOVED***
                    else {
                        $coursesObj = new Course($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);
                        $courseDAO->addCourses($coursesObj);
                        $courses_processed++;
                ***REMOVED***

                    $course_row++;
            ***REMOVED***

                // Begin importing section.csv.
                $header = fgetcsv($sections); // Skip first row.
                $section_row = 2;
                while (($data = fgetcsv($sections)) !== false) {
                    $data = array_map('trim', $data);

                    $validationErrors = [];
                    
                    $validationErrors = commonValidation($data, $header);

                    // If common validation fails, we don't even proceed with file-specific validation
                    if (!$validationErrors) {
                        $validationErrors = validateSection($data);
                ***REMOVED***

                    if ($validationErrors) {
                        // If errors exist.
                        $errors[] = [
                            'file' => 'section.csv',
                            'line' => $section_row,
                            'message' => $validationErrors
                        ];
                ***REMOVED***
                    else {
                        $sectionsObj = new Section($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7]);
                        $sectionDAO->add($sectionsObj);
                        $sections_processed++;
                ***REMOVED***

                    $section_row++;
            ***REMOVED***

                // Begin importing prerequisite.csv.
                $header = fgetcsv($prerequisites); // Skip first row.
                $prerequisite_row = 2;
                while (($data = fgetcsv($prerequisites)) !== false) {
                    $data = array_map('trim', $data);

                    $validationErrors = [];
                    
                    $validationErrors = commonValidation($data, $header);

                    // If common validation fails, we don't even proceed with file-specific validation
                    if (!$validationErrors) {
                        $validationErrors = validatePrerequisite($data);
                ***REMOVED***

                    if ($validationErrors) {
                        // If errors exist.
                        $errors[] = [
                            'file' => 'prerequisite.csv',
                            'line' => $prerequisite_row,
                            'message' => $validationErrors
                        ];
                ***REMOVED***
                    else {
                        $course = $data[0];
                        $prerequisite = $data[1];
                        $courseDAO->addPrerequisites($course, $prerequisite);
                        $prerequisites_processed++;
                ***REMOVED***

                    $prerequisite_row++;
            ***REMOVED***

                // Begin importing course_completed.csv.
                $header = fgetcsv($courses_completed); // Skip first row.
                $courses_completed_row = 2;
                while (($data = fgetcsv($courses_completed)) !== false) {
                    $data = array_map('trim', $data);

                    $validationErrors = [];
                    
                    $validationErrors = commonValidation($data, $header);

                    // If common validation fails, we don't even proceed with file-specific validation
                    if (!$validationErrors) {
                        $validationErrors = validateCourseCompletion($data);
                ***REMOVED***

                    if ($validationErrors) {
                        // If errors exist.
                        $errors[] = [
                            'file' => 'course_completed.csv',
                            'line' => $courses_completed_row,
                            'message' => $validationErrors
                        ];
                ***REMOVED***
                    else {
                        $userId = $data[0];
                        $code = $data[1];
                        $courseDAO->addCompletedCourses($userId, $code);
                        $courses_completed_processed++;
                ***REMOVED***

                    $courses_completed_row++;
            ***REMOVED***

                // Before we import bid.csv, we sort our $dataSections array to increase code performance.
                /*global $dataSections;

                foreach ($dataSections as $keyCourse => $course) {
                    foreach ($course as $keySection => $section) {
                        $dataSectionsCompressed[] = [
                            'course' => $keyCourse,
                            'section' => $keySection,
                            'day' => $section['day'],
                            'start' => $section['start'],
                            'end' => $section['end']
                        ];
                ***REMOVED***
            ***REMOVED***

                array_multisort(
                    array_column($dataSectionsCompressed, 'day'), SORT_ASC,
                    array_map('strtotime', array_column($dataSectionsCompressed, 'start')), SORT_ASC,
                    $dataSectionsCompressed
                );
                
                print_r($dataSectionsCompressed);*/

                // Begin importing bid.csv.
                $header = fgetcsv($bids); // Skip first row.
                $bids_row = 2;
                while (($data = fgetcsv($bids)) !== false) {
                    $data = array_map('trim', $data);
                    
                    $validationErrors = commonValidation($data, $header);

                    // If common validation fails, we don't even proceed with file-specific validation
                    if (!$validationErrors) {
                        $validationErrors = validateBid($data);
                ***REMOVED***

                    if ($validationErrors) {
                        // If errors exist.
                        $errors[] = [
                            'file' => 'bid.csv',
                            'line' => $bids_row,
                            'message' => $validationErrors
                        ];
                ***REMOVED***
                    else {
                        $userId = $data[0];
                        $amount = $data[1];
                        $course = $data[2];
                        $section = $data[3];

                        $bidDAO->addBidBootstrap($userId, $course, $section, $amount);
                        $bids_processed++;
                ***REMOVED***
                    $bids_row++;
            ***REMOVED***


                fclose($courses);
                @unlink($courses_path);

                fclose($courses_completed);
                @unlink($courses_completed_path);

                fclose($prerequisites);
                @unlink($prerequisites_path);

                fclose($bids);
                @unlink($bids_path);

                fclose($sections);
                @unlink($sections_path);

                fclose($students);
                @unlink($students_path);

                $roundDAO->startRound();


        ***REMOVED***
    ***REMOVED***
***REMOVED***

    if ($errors) {
        array_multisort(
            array_column($errors, 'file'), SORT_ASC,
            array_column($errors, 'line'), SORT_ASC,
            $errors
        );

        $result = [
            "status" => "error",
            "num-record-loaded" => [
                ["bid.csv" => $bids_processed],
                ["course.csv" => $courses_processed],
                ["course_completed.csv" => $courses_completed_processed],
                ["prerequisite.csv" => $prerequisites_processed],
                ["section.csv" => $sections_processed],
                ["student.csv" => $students_processed]
            ],
            "error" => $errors
        ];
***REMOVED***
    else {
        $result = [
            "status" => "success",
            "num-record-loaded" => [
                ["bid.csv" => $bids_processed],
                ["course.csv" => $courses_processed],
                ["course_completed.csv" => $courses_completed_processed],
                ["prerequisite.csv" => $prerequisites_processed],
                ["section.csv" => $sections_processed],
                ["student.csv" => $students_processed]
            ]
        ];
***REMOVED***

    return json_encode($result, JSON_PRETTY_PRINT);


}

?>