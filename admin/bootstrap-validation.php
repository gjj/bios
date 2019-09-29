<?php

require_once '../includes/common.php';

// $data is an array of each row values
function hasEmptyField($data){
    $columnpos_arr = [];
    for ($i = 0; $i <=count($data); $i++) {
        // Make sure that the key exists, isn't null or an empty string
        if (!isset($data[$i]) || $data[$i] == '') {
            $columnpos_arr[] = $i;
        }
    }

    return $columnpos_arr; // position of columns with missing values 
}
// incomplete
// function commonValidation($file){

//     $counter = 0;
//     $errors= [];
//     while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {        
//         $counter++;    
//         $columnpos_arr = hasEmptyField($data);
//         if (!empty($columnpos_arr)) {
//             foreach($columnpos as $columnpos_arr){

//             }
//              $errors[] = "Empty field is in row: $counter cell $column_error";
//         }


//     }

    // echo $errors;


// }

    function studentValidation($data){
        $userId = $data[0];
        $password = $data[1];
        $name = $data[2];
        $school = $data[3];
        $edollar = $data[4];

        $errors = [];
        $userDAO = new UserDAO();

        if(strlen($userId)>128){
            $error = "invalid userid";
            $errors[] = $error; 
        }
        if($userDAO -> retrieveById($userId) != null) {
            $error = "duplicate userid";
            $errors[] = $error; 
        }
        if(is_numeric($edollar) == False || $edollar < 0.0 || $edollar != round($edollar,2) ) {
            $error = "invalid e-dollar";
            $errors[] = $error; 
        }
        if(strlen($password)>128){
            $error = "invalid password";
            $errors[] = $error; 
        }
        if(strlen($name)>100){
            $error = "invalid name";    
            $errors[] = $error; 
        }

        // if error not null, delete row 
        if($errors != []) {
            $sql="DELETE FROM users WHERE user_id = :userId";

            $connMgr = new ConnectionManager();
            $db = $connMgr->getConnection();

            $query = $db->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->bindParam(':userId', $userId, PDO::PARAM_STR);
    
            $query->execute();
            $query->fetch(PDO::FETCH_ASSOC);

             
        }
        return $errors; 
         
    }

    
    function courseValidation($data){
        $course = $data[0];
        $school = $data[1];
        $title = $data[2];
        $description = $data[3];
        $examdate = $data[4];
        $examstart = $data[5];
        $examend = $data[6];

        $errors = [];
        $result = True; 
        if(strlen($title)>100){
            $result = False;
            $error = "invalid title";
            $errors[] = $error; 
        }
        $year = "";
        $month = "";
        $day = "";
        for($i=0;$i<strlen($examdate);$i++){
            if($i>=0 && $i<=3){
                $year .= $examdate[$i];
            }
            elseif($i>3 && $i<=5){
                $month .= $examdate[$i];
            }
            else{
                $day .= $examdate[$i];
            }
        }
        if(checkdate(intval($month),intval($day),intval($year))!= True){
            $error = "invalid exam date";
            $errors[] = $error; 
        }
        if(preg_match("/([0-9]{1,2}:[0-9]{2})/", $examstart)!= True){
            $error = "invalid exam start";
            $errors[] = $error;
        }
        if(preg_match("/([0-9]{1,2}:[0-9]{2})/", $examend)!= True) {
        //|| $examstart > $examend){
            $error = "invalid exam end" . ($examstart > $examend);
            $errors[] = $error;
        }
        if(strlen($description)>1000){
            $error = "invalid description";
            $errors[] = $error;
        }

        // if error not null, delete row 
        if($errors != []) {
            $sql="DELETE FROM courses WHERE course = :course";

            $connMgr = new ConnectionManager();
            $db = $connMgr->getConnection();

            $query = $db->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->bindParam(':course', $course, PDO::PARAM_STR);
    
            $query->execute();
            $query->fetch(PDO::FETCH_ASSOC);

            
        }
        return $errors; 

        
    }

    function checkCoursecode($course){
        $courseDAO = new CourseDAO();
        $result = True;
        if($courseDAO -> retrieveByCode($course) == null){
            $result = False;
        }
        return $result;
    }
    function checkSectionNum($section){
        $result = True;
        $section_num = "";
        for($i=0;$i<strlen($section);$i++){
            if($i != 0){
                $section_num .= $section[$i];
            }
        }
        echo $section_num;
        $section_num = intval($section_num);
        if($section_num<=0 || $section_num>99){
            $result = False;
        }
        return $result;
    }
    function sectionValidation($data) {
        $course = $data[0];
        $section = $data[1];
        $day = $data[2];
        $start = $data[3];
        $end = $data[4];
        $instructor = $data[5];
        $venue = $data[6];
        $size = $data[7];

        $errors = [];

        if(!checkCoursecode($course)){
            $error = "invalid course";
            $errors[] = $error;
        }
        else{
            if($section[0] != "S" || !checkSectionNum($section) ){
                $error = "invalid section";
                $errors[] = $error;
            }
            if($day<1 || $day>7){
                $error = "invalid day";
                $errors[] = $error;
            }
            if(preg_match("/([0-9]{1,2}:[0-9]{2})/", $start)!= True){
                $error = "invalid exam start: {$start}";
                $errors[] = $error;
            }
            if(preg_match("/([0-9]{1,2}:[0-9]{2})/", $end)!= True){
            //|| $start > $end){
                $error = "invalid exam end {$end}";
                $errors[] = $error;
            }
            if(strlen($instructor)>100){
                $error = "invalid instructor";
                $errors[] = $error; 
            }
            if(strlen($venue)>100){
                $error = "invalid venue";
                $errors[] = $error; 
            }
            if(!is_numeric($size) || $size<1){
                $error = "invalid size";
                $errors[] = $error; 
            }
        }

        // if error not null, delete row and return errors
        if($errors != []) {
            $sql="DELETE FROM sections WHERE course = :course";

            $connMgr = new ConnectionManager();
            $db = $connMgr->getConnection();

            $query = $db->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->bindParam(':course', $course, PDO::PARAM_STR);
    
            $query->execute();
            $query->fetch(PDO::FETCH_ASSOC);

            
        } 
        return $errors; 


        
}

    function prerequisiteValidation($data){
        $course = $data[0];
        $prerequisite = $data[1];
        
        $errors = [];

        if(!checkCoursecode($course)){
            $error = "invalid course";
            $errors[] = $error;
        }

        if(!checkCoursecode($prerequisite)){
            $error = "invalid prerequisite";
            $errors[] = $error;
        }

        # if error not null, delete row and return errors
        if($errors != []) {
            $sql="DELETE FROM prerequisites WHERE course = :course";

            $connMgr = new ConnectionManager();
            $db = $connMgr->getConnection();

            $query = $db->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->bindParam(':course', $course, PDO::PARAM_STR);
    
            $query->execute();
            $query->fetch(PDO::FETCH_ASSOC);

            
        } 
        return $errors; 

    }

    function checkUserId($userId){
        $userDAO = new UserDAO();
        $result = True;
        if($userDAO ->  retrieveById($userId) == null){
            $result = False;
        }
        return $result;
    }

    // Haven't completed yet
    // function checkPrerequisites($course_completed){
    //     $courseDAO = new CourseDAO();
        
    //     if($courseDAO -> searchPrerequisites($course_completed) != null){

    //     }
    // }
    function courseCompletedValidation($data){
        $userId = $data[0];
        $course_completed = $data[1];

        $errors = [];

        if(!checkUserId($userId)){
            $error = "invalid userid";
            $errors[] = $error;
        }

        if(!checkCoursecode($course_completed)){
            $error = "invalid course";
            $errors[] = $error;
        }

        # if error not null, delete row and return errors
        if($errors != []) {
            $sql="DELETE FROM courses_completed WHERE user_id = :userId";

            $connMgr = new ConnectionManager();
            $db = $connMgr->getConnection();

            $query = $db->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->bindParam(':userId', $userId, PDO::PARAM_STR);
    
            $query->execute();
            $query->fetch(PDO::FETCH_ASSOC);
        }
        // Logic validation missing 
        // else{

        // }
        return $errors; 
    }

    // Bid Validations 
    function checkSection($course, $section){
        $sectionDAO = new SectionDAO();
        $result = True; 
        if ($sectionDAO -> sectionExists($course, $section) == null){
            $result = False;
        }
        return $result;
    }
    function bidValidation($data){
        $id = $data[0];
        $userId = $data[1];
        $amount = $data[2];
        $course = $data[3];
        $section = $data[4];

        $errors = [];

        if(!checkUserId($userId)){
            $error = "invalid userid";
            $errors[] = $error;
        }
        if(is_numeric($amount) == False || $amount < 10 || $amount != round($amount,2)){
            $error = "invalid amount";
            $errors[] = $error;
        }
        if(!checkCoursecode($course)){
            $error = "invalid course";
            $errors[] = $error;
        }
        else{
            if(!checkSection($course, $section)){
                $error = "invalid section";
                $errors[] = $error;
            }   
        }
        return $errors;
    }
    




?>