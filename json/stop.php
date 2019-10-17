***REMOVED***
    require_once '../includes/common.php';
    require_once '../includes/round.php';

    header("Content-Type: application/json");
    
    // Remember to add token validation here...

    $result = doStop();
    echo $result;