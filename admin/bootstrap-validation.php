<?php

require_once '../includes/common.php';
// commonValidation to check each file, if there's a row with any of the column data missing, discard that row
// incomplete
function hasEmptyField(array $data)
{
    for ($i = 0; $i <=len($data); $i++) {
        // Make sure that the key exists, isn't null or an empty string
        if (!isset($data[$i]) || $data[$i] === '') {
            
            return $i;
        }
    }

    return false;
}
// incomplete
function commonValidation($file){

    $counter = 0;

    while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {        

        $counter++;    
        $errors= [];
    
        if (hasEmptyField($data) >= 0) {
            // It has an empty field. Echo an error and skip to next row
             $errors+= ["Empty field is in row: $counter"];
             continue;
        }

    }

    echo $errors;
}

// commonValidation($courses);
?>