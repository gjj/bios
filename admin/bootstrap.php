***REMOVED***
require_once '../includes/common.php';

function doBootstrap() {

	$errors = array();
	# need tmp_name -a temporary name create for the file and stored inside apache temporary folder- for proper read address
	$zip_file = $_FILES["bootstrap-file"]["tmp_name"];

	# Get temp dir on system for uploading
	$temp_dir = sys_get_temp_dir();

	# keep track of number of lines successfully processed for each file
	$students_processed=0;
	$bids_processed=0;
	$courses_processed=0;
    $courses_completed_processed=0;
    $prerequisites_processed=0;
    $sections_processed=0;

	# check file size
	if ($_FILES["bootstrap-file"]["size"] <= 0)
		$errors[] = "input files not found";

	else {
		
		$zip = new ZipArchive;
		$res = $zip->open($zip_file);

		if ($res === TRUE) {
			$zip->extractTo($temp_dir);
            $zip->close();
            
			// Not completed
			$students_path = "$temp_dir/student.csv";
			$bids_path = "$temp_dir/bid.csv";
			$courses_path = "$temp_dir/course.csv";
            $courses_completed_path = "$temp_dir/course_completed.csv";
            $prerequisites_path = "$temp_dir/prerequisite.csv";
            $sections_path = "$temp_dir/section.csv";
            
			$students = @fopen($students_path, "r");
			$bids = @fopen($bids_path, "r");
			$courses = @fopen($courses_path, "r");
            $courses_completed = @fopen($courses_completed_path, "r");
            $prerequisites = @fopen($prerequisites_path, "r");
			$sections = @fopen($sections_path, "r");
			
			if (empty($courses)){
            // if (empty($bids) || empty($courses) || empty($courses_completed)
            // || empty($prerequisites)|| empty($sections)){#|| empty($users)|| empty($rounds)){
				$errors[] = "input files not found";
				if (!empty($students)){
					fclose($students);
					@unlink($students_path);
            ***REMOVED*** 
				if (!empty($bids)){
					fclose($bids); 
					@unlink($bids_path);
				} 
				
				if (!empty($courses)) {
					fclose($courses);
					@unlink($courses_path);
				}
				
				if (!empty($courses_completed)) {
					fclose($courses_completed);
					@unlink($courses_completed_path);
            ***REMOVED***
                if (!empty($prerequisites)){
					fclose($prerequisites);
					@unlink($prerequisites_path);
				} 
				
                if (!empty($sections)){
					fclose($sections);
					@unlink($sections_path);
            ***REMOVED*** 

				
				
			}
			else {
				$connMgr = new ConnectionManager();
				$conn = $connMgr->getConnection();

				// Truncate all tables 
				$userDAO = new UserDAO();
				$userDAO -> removeAll();

				$BidDAO = new BidDAO();
				$BidDAO -> removeAll();

				$courseDAO = new CourseDAO();
                $courseDAO -> removeAllCourses();
                $courseDAO -> removeAllCompletedCourses();
                $courseDAO -> removeAllPrerequisites();

                // $roundDAO = new RoundDAO();
                // $roundDAO -> removeAll();

                $sectionDAO = new SectionDAO();
				$sectionDAO -> removeAll();

				$data = fgetcsv($students);
				while(($data = fgetcsv($students)) !== false){
					$studentObj = new User( $data[0], $data[1], $data[2],$data[3], $data[4], $data[5]);
					$userDAO->add($studentObj);
					$students_processed++;
				}

				$data = fgetcsv($courses);
				while(($data = fgetcsv($courses)) !== false){
					$coursesObj = new Course( $data[0], $data[1], $data[2],$data[3], $data[4], $data[5],$data[6]);
					$courseDAO->addCourses($coursesObj);
					$courses_path++;
				}
				$data = fgetcsv($sections);
				while(($data = fgetcsv($sections)) !== false){
					$sectionsObj = new Section( $data[0], $data[1], $data[2],$data[3], $data[4], $data[5],$data[6], $data[7]);
					$sectionDAO->add($sectionsObj);
					$sections_processed++;
				}
				$data = fgetcsv($courses_completed);
				while(($data = fgetcsv($courses_completed)) !== false){
					$userId = $data[0];
					$completed_course = $data[1];
					// $courses_completedObj = new Section( $data[0], $data[1], $data[2],$data[3], $data[4], $data[5],$data[6], $data[7]);
					$courseDAO->add($userId, $completed_course);
					$courses_completed_processed++;
				}


                fclose($courseDAO);
				@unlink($courses_path);

				// fclose($BidDAO);
                // @unlink($bids_path);
                
                fclose($sectionDAO);
				@unlink($sections_path);
			    
                fclose($userDAO);
				@unlink($students_path);
			}
		}
***REMOVED***
     
    if (!isEmpty($errors))
	{	
		$sortclass = new Sort();
		$errors = $sortclass->sort_it($errors,"bootstrap");
		$result = [ 
			"status" => "error",
			"messages" => $errors
		];
	}

	else
	{	
		$result = [ 
			"status" => "success",
			"num-record-loaded" => [
				"student.csv" => $students_processed,
				"bid.csv" => $bids_processed,
				"course.csv" => $courses_processed,
                "course_completed.csv" => $courses_completed_processed,
                "prerequisite.csv" => $prerequisites_processed,
                "section.csv" => $sections_processed
			]
		];
	}
	header('Content-Type: application/json');
	echo json_encode($result, JSON_PRETTY_PRINT);


}
?>