<?php
    header('Content-Type: application/json');
    
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
    }
    else {
        echo doBootstrap();        
    }
?>