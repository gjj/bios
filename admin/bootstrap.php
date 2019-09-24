***REMOVED***
require_once '../includes/common.php';

function doBootstrap() {

	$errors = array();
	# need tmp_name -a temporary name create for the file and stored inside apache temporary folder- for proper read address
	$zip_file = $_FILES["bootstrap-file"]["tmp_name"];

	# Get temp dir on system for uploading
	$temp_dir = sys_get_temp_dir();

	# keep track of number of lines successfully processed for each file
	$bids_processed=0;
	$courses_processed=0;
    $courses_completed_processed=0;
    $prerequisites_processed=0;
    $rounds_processed=0;
    $sections_processed=0;
    $users_processed=0;

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
			// $bids_path = "$temp_dir/";
			$bids_path = "$temp_dir/bid.csv";
			$courses_path = "$temp_dir/course.csv";
            $courses_completed_path = "$temp_dir/course_completed.csv";
            $prerequisites_path = "$temp_dir/prerequisite.csv";
            // $rounds_path = "$temp_dir/";
            $sections_path = "$temp_dir/section.csv";
            // $users_path = "$temp_dir/";
			
			$bids = @fopen($bids, "r");
			$courses = @fopen($courses_path, "r");
            $courses_completed = @fopen($courses_completed_path, "r");
            $prerequisites = @fopen($prerequisites_path, "r");
            // $rounds = @fopen($rounds_path, "r");
			$sections = @fopen($sections_path, "r");
			// $users = @fopen($users_path, "r"); 
			
            if (empty($bids) || empty($courses) || empty($courses_completed)
            || empty($prerequisites)|| empty($rounds)|| empty($sections)|| empty($users)){
				$errors[] = "input files not found";
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
            ***REMOVED*** 
                // if (!empty($rounds)){
				// 	fclose($rounds);
				// 	@unlink($rounds_path);
                // } 
                if (!empty($sections)){
					fclose($sections);
					@unlink($sections_path);
            ***REMOVED*** 
                // if (!empty($users)){
				// 	fclose($users);
				// 	@unlink($users_path);
                // } 
				
				
			}
			else {
				$connMgr = new ConnectionManager();
				$conn = $connMgr->getConnection();

				# start processing
				
				# truncate current SQL tables
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

                // $userDAO = new UserDAO();
                // $userDAO -> removeAll();

                fclose($courseDAO);
				@unlink($courses_path);

                fclose($BidDAO);
                @unlink($bids_path);
                
                fclose($sectionDAO);
                @unlink($sections_path);
                
                // fclose($userDAO);
				// @unlink($users_path);
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
				"pokemon.csv" => $pokemon_processed,
				"pokemon_type.csv" => $pokemon_type_processed,
				"User.csv" => $User_processed
			]
		];
	}
	header('Content-Type: application/json');
	echo json_encode($result, JSON_PRETTY_PRINT);


}
?>