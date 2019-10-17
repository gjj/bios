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
            "messages" => array_values($errors)
        ];
***REMOVED***
    else {
        echo doBootstrap();        
***REMOVED***
?>