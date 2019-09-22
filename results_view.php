
<?php
    require_once 'includes/common.php';

    if (!isLoggedIn()) {
		header("Location: .");
    }
    else {
        $userId = $_SESSION['userid'];
        $bidDAO = new BidDAO();
        $biddingResults = $bidDAO -> retrieveResults($userId);
        // Display results in table form 
    }
?>