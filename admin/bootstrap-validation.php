***REMOVED***

require_once '../includes/common.php';

// $data is an array of each row values
function hasEmptyField($data){
    $columnpos_arr = [];
    for ($i = 0; $i <=count($data); $i++) {
        // Make sure that the key exists, isn't null or an empty string
        if (!isset($data[$i]) || $data[$i] == '') {
            $columnpos_arr[] = $i;
    ***REMOVED***
***REMOVED***

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

//         ***REMOVED***
//              $errors[] = "Empty field is in row: $counter cell $column_error";
//     ***REMOVED***


// ***REMOVED***

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
    ***REMOVED***
        if($userDAO -> retrieveById($userId) != null) {
            $error = "duplicate userid";
            $errors[] = $error; 
    ***REMOVED***
        if(is_numeric($edollar) == False || $edollar < 0.0 || $edollar != round($edollar,2) ) {
            $error = "invalid e-dollar";
            $errors[] = $error; 
    ***REMOVED***
        if(strlen($password)>128){
            $error = "invalid password";
            $errors[] = $error; 
    ***REMOVED***
        if(strlen($name)>100){
            $error = "invalid name";    
            $errors[] = $error; 
    ***REMOVED***

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

             
    ***REMOVED***
        return $errors; 
         
***REMOVED***

    
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
    ***REMOVED***
        $year = "";
        $month = "";
        $day = "";
        for($i=0;$i<strlen($examdate);$i++){
            if($i>=0 && $i<=3){
                $year .= $examdate[$i];
        ***REMOVED***
            elseif($i>3 && $i<=5){
                $month .= $examdate[$i];
        ***REMOVED***
            else{
                $day .= $examdate[$i];
        ***REMOVED***
    ***REMOVED***
        if(checkdate(intval($month),intval($day),intval($year))!= True){
            $error = "invalid exam date";
            $errors[] = $error; 
    ***REMOVED***
        if(preg_match("/([0-9]{1,2}:[0-9]{2})/", $examstart)!= True){
            $error = "invalid exam start";
            $errors[] = $error;
    ***REMOVED***
        if(preg_match("/([0-9]{1,2}:[0-9]{2})/", $examend)!= True) {
        //|| $examstart > $examend){
            $error = "invalid exam end" . ($examstart > $examend);
            $errors[] = $error;
    ***REMOVED***
        if(strlen($description)>1000){
            $error = "invalid description";
            $errors[] = $error;
    ***REMOVED***

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

            
    ***REMOVED***
        return $errors; 

        
***REMOVED***

    function checkCoursecode($course){
        $courseDAO = new CourseDAO();
        $result = True;
        if($courseDAO -> retrieveByCode($course) == null){
            $result = False;
    ***REMOVED***
        return $result;
***REMOVED***
    function checkSectionNum($section){
        $result = True;
        $section_num = "";
        for($i=0;$i<strlen($section);$i++){
            if($i != 0){
                $section_num .= $section[$i];
        ***REMOVED***
    ***REMOVED***
        echo $section_num;
        $section_num = intval($section_num);
        if($section_num<=0 || $section_num>99){
            $result = False;
    ***REMOVED***
        return $result;
***REMOVED***
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
    ***REMOVED***
        else{
            if($section[0] != "S" || !checkSectionNum($section) ){
                $error = "invalid section";
                $errors[] = $error;
        ***REMOVED***
            if($day<1 || $day>7){
                $error = "invalid day";
                $errors[] = $error;
        ***REMOVED***
            if(preg_match("/([0-9]{1,2}:[0-9]{2})/", $start)!= True){
                $error = "invalid exam start: {$start}";
                $errors[] = $error;
        ***REMOVED***
            if(preg_match("/([0-9]{1,2}:[0-9]{2})/", $end)!= True){
            //|| $start > $end){
                $error = "invalid exam end {$end}";
                $errors[] = $error;
        ***REMOVED***
            if(strlen($instructor)>100){
                $error = "invalid instructor";
                $errors[] = $error; 
        ***REMOVED***
            if(strlen($venue)>100){
                $error = "invalid venue";
                $errors[] = $error; 
        ***REMOVED***
            if(!is_numeric($size) || $size<1){
                $error = "invalid size";
                $errors[] = $error; 
        ***REMOVED***
    ***REMOVED***

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

            
    ***REMOVED*** 
        return $errors; 


        
}

    function prerequisiteValidation($data){
        $course = $data[0];
        $prerequisite = $data[1];
        
        $errors = [];

        if(!checkCoursecode($course)){
            $error = "invalid course";
            $errors[] = $error;
    ***REMOVED***

        if(!checkCoursecode($prerequisite)){
            $error = "invalid prerequisite";
            $errors[] = $error;
    ***REMOVED***

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

            
    ***REMOVED*** 
        return $errors; 

***REMOVED***

    function checkUserId($userId){
        $userDAO = new UserDAO();
        $result = True;
        if($userDAO ->  retrieveById($userId) == null){
            $result = False;
    ***REMOVED***
        return $result;
***REMOVED***

    // Haven't completed yet
    // function checkPrerequisites($course_completed){
    //     $courseDAO = new CourseDAO();
        
    //     if($courseDAO -> searchPrerequisites($course_completed) != null){

    // ***REMOVED***
    // }
    function courseCompletedValidation($data){
        $userId = $data[0];
        $course_completed = $data[1];

        $errors = [];

        if(!checkUserId($userId)){
            $error = "invalid userid";
            $errors[] = $error;
    ***REMOVED***

        if(!checkCoursecode($course_completed)){
            $error = "invalid course";
            $errors[] = $error;
    ***REMOVED***

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
    ***REMOVED***
        // Logic validation missing 
        // else{

        // }
        return $errors; 
***REMOVED***

    // Bid Validations 
    function checkSection($course, $section){
        $sectionDAO = new SectionDAO();
        $result = True; 
        if ($sectionDAO -> sectionExists($course, $section) == null){
            $result = False;
    ***REMOVED***
        return $result;
***REMOVED***
    function bidValidation($data){
        $userId = $data[0];
        $amount = $data[1];
        $course = $data[2];
        $section = $data[3];

        $errors = [];

        if(!checkUserId($userId)){
            $error = "invalid userid";
            $errors[] = $error;
    ***REMOVED***
        if(is_numeric($amount) == False || $amount < 10 || $amount != round($amount,2)){
            $error = "invalid amount";
            $errors[] = $error;
    ***REMOVED***
        if(!checkCoursecode($course)){
            $error = "invalid course";
            $errors[] = $error;
    ***REMOVED***
        else{
            if(!checkSection($course, $section)){
                $error = "invalid section";
                $errors[] = $error;
        ***REMOVED***   
    ***REMOVED***
        return $errors;
***REMOVED***
    




?>