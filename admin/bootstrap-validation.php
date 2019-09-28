<?php

require_once '../includes/common.php';
// commonValidation to check each file, if there's a row with any of the column data missing, discard that row
// incomplete
function hasEmptyField(array $data)
{
    $counter_column = [];
    for ($i = 0; $i <=len($data); $i++) {
        // Make sure that the key exists, isn't null or an empty string
        if (!isset($data[$i]) || $data[$i] === '') {
            $counter_column += [$i];
        }
    }

    return $counter_column;
}
// incomplete
function commonValidation($file){

    $counter = 0;
    $errors= [];
    while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {        

        $counter++;    
        $column_error = hasEmptyField($data);
        if ($column_error != []) {
            // It has an empty field. Echo an error and skip to next row
             $errors+= ["Empty field is in row: $counter cells $column_error"];
             continue;
        }

    }

    echo $errors;
}

// commonValidation($courses);
?>

