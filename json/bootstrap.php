***REMOVED***
    header('Content-Type: application/json');
    
    require_once '../includes/common.php';
    require_once '../includes/bootstrap.php';
    
    $errors = [
        isMissingOrEmpty('token')
    ];

    $errors = array_filter($errors);

    if (!isEmpty($errors)) {
        $result = [
            "status" => "error",
            "message" => array_values($errors)
        ];

        echo $result;
***REMOVED***
    else {
        echo doBootstrap();
***REMOVED***
?>