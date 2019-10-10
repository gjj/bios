***REMOVED***
    require_once '../includes/common.php';
    require_once '../admin/bootstrap.php';
    
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
        doBootstrap();        
***REMOVED***
?>