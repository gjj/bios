<?php
require_once '../includes/common.php';
require_once 'bootstrap-validation.php';
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
			
            if ((empty($bids) || empty($courses) || empty($courses_completed)
            || empty($prerequisites)|| empty($sections)) || empty($students)){
				$errors[] = "input files not found";
				if (!empty($students)){
					fclose($students);
					@unlink($students_path);
                } 
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
                }
                if (!empty($prerequisites)){
					fclose($prerequisites);
					@unlink($prerequisites_path);
				} 
				
                if (!empty($sections)){
					fclose($sections);
					@unlink($sections_path);
                } 	
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

                $roundDAO = new RoundDAO();
                $roundDAO -> removeAll();

                $sectionDAO = new SectionDAO();
				$sectionDAO -> removeAll();


				$data = fgetcsv($students);

				// if(hasEmptyField($data) != []){
				// 	$missing_fields = [];
				// 	foreach($columnpos_arr as $columnpos){
				// 		$missing_fields[] = $data[$columnpos];
				// 	}
				// 	foreach($missing_fields as $missing_field){
				// 		$errors[] = "blank $missing_field";
				// 	}
				// }
				$student_row = 1;
				while(($data = fgetcsv($students)) !== false){
					if(studentValidation($data) != []){
						$student_errors = studentValidation($data);
						foreach($student_errors as $student_error){
							$error = "$student_error in row $student_row in student.csv";
							$errors[] = $error; 
						}
						// $error = "$student_errors in row $student_row in student.csv";
						// $errors[] = $error; 
						
					}
					else{
						$studentObj = new User( $data[0], $data[1], $data[2],$data[3], $data[4]);
						$userDAO->add($studentObj);
						$students_processed++;
					}
					$student_row ++;

				}
				

				$data = fgetcsv($courses);
				$course_row = 1;
				while(($data = fgetcsv($courses)) !== false){
					if(courseValidation($data) != []){
						$course_errors = courseValidation($data);
						foreach($course_errors as $course_error){
							$error = "$course_error in row $course_row in course.csv";
							$errors[] = $error; 
						}
						// $error = "$course_errors in row $course_row in course.csv";
						// $errors[] = $error; 
						
					}
					else{
						$coursesObj = new Course( $data[0], $data[1], $data[2],$data[3], $data[4], $data[5],$data[6]);
						$courseDAO->addCourses($coursesObj);
						$courses_processed++;
					}
					$course_row ++;
				}


				$data = fgetcsv($sections);
				$section_row = 1;
				while(($data = fgetcsv($sections)) !== false){
					if(sectionValidation($data) != []){
						$section_errors = sectionValidation($data);
						foreach($section_errors as $section_error){
							$error = "$section_error in row $section_row in section.csv";
							$errors[] = $error;
						}
						// $error = "$section_errors in row $section_row in section.csv";
						// $errors[] = $error;
					}
					else{
						$sectionsObj = new Section( $data[0], $data[1], $data[2],$data[3], $data[4], $data[5],$data[6], $data[7]);
						$sectionDAO->add($sectionsObj);
						$sections_processed++;
					}
					$section_row ++;
				}

				$data = fgetcsv($courses_completed);
				while(($data = fgetcsv($courses_completed)) !== false){
					$userId = $data[0];
					$code = $data[1];
					$courseDAO->addCompletedCourses($userId, $code);
					$courses_completed_processed++;
				}
				$data = fgetcsv($prerequisites);
				while(($data = fgetcsv($prerequisites)) !== false){
					$course = $data[0];
					$prerequisite = $data[1];
					$courseDAO->addPrerequisites($course,$prerequisite);
					$prerequisites_processed++;
				}
				$data = fgetcsv($bids);
				while(($data = fgetcsv($bids)) !== false){
					$userId = $data[0];
					$amount = $data[1];
					$code = $data[2];
					$section = $data[3];

					$BidDAO->add($userId,$amount,$code,$section);
					$bids_processed++;
				}

                fclose($courses);
				@unlink($courses_path);
				@unlink($courses_completed_path);
				@unlink($prerequisites_path);

				fclose($bids);
                @unlink($bids_path);
                
                fclose($sections);
				@unlink($sections_path);
			    
                fclose($students);
				@unlink($students_path);
				

				
			}
		}
    }
     
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