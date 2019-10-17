<?php
define("BIOS_ADMIN", true);

require_once '../includes/common.php';
require_once '../includes/round.php'; // includes function for doStart() and doStop()


if (!isset($_SESSION['userid'])) {
    header("Location: .");
}
// Create RoundDAO
$roundDAO = new RoundDAO();

//Retrieve Current Active Round
$currentRound = $roundDAO->getCurrentRound();

//Set necessary Stuff
if ($currentRound['status'] != "stopped") {
    $currentActive = $currentRound['round'];
    $setStatus = "Stop";
    // $setLink = "home.php";
} else {
    $currentActive = "None";
    $setStatus = "Start";
    //$setLink = "home.php";
}

// Check if isset
if (isset($_GET['value']) and $_GET['value'] == 'Stop') {
    /*// create a new cURL resource
    $ch = curl_init();
    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, "http://18.136.126.161/app/json/stop");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // grab URL and pass it to the browser
    $result = curl_exec($ch);
    $result = json_decode($result, true);
    // close cURL resource, and free up system resources
    curl_close($ch);
    $_SESSION['result'] = $result;
    //Refresh Page*/

    $_SESSION['result'] = doStop();

    header('Location: ' . $_SERVER['PHP_SELF']);
} elseif (isset($_GET['value']) and $_GET['value'] == 'Start') {
    /*// create a new cURL resource
    $ch = curl_init();
    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, "http://18.136.126.161/app/json/start");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // grab URL and pass it to the browser
    $result = curl_exec($ch);
    $result = json_decode($result, true);
    $_SESSION['result'] = $result;*/

    $_SESSION['result'] = doStart();
    //Refresh Page
    header('Location: ' . $_SERVER['PHP_SELF']);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <base href="../">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>BIOS | Merlion University</title>

    <link href="assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="assets/css/fontawesome-all.min.css" rel="stylesheet"/>
    <link href="assets/css/bios.css" rel="stylesheet"/>
</head>
<body>
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">BIOS</a>
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="logout">Sign out</a>
        </li>
    </ul>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="admin/home">
                            Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/bootstrap-page">
                            Bootstrap</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Home</h1>
            </div>
            <p class="lead">
                Current Active Round <code><?php echo $currentActive; ?></code>.
            </p>

            <div class="row">
                <div class="col-md-12">
                    <b><?php
                        if (isset($_SESSION['result'])) {
                            $result = $_SESSION['result'];
                        ?>
                            <b>Status:</b> <?php echo $result['status']; ?><br/>
                            <?php if ($result['status'] == "error") { ?>
                                <b>Error Message : </b><?php echo $result['messages']; ?><br/>
                            <?php
                                    unset($_SESSION['result']);
                                }
                        }
                        ?>
                    </b>
                </div>
            </div>
            <form id='form_submit' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get"
                  enctype="multipart/form-data">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                    <input type="submit" name="submit" class="btn btn-dark" value="<?php echo $setStatus; ?>"></button>
                    <input type="hidden" id="value" name="value" value="<?php echo $setStatus; ?>">
                </div>
            </form>
        </main>
    </div>
</div>
</body>
</html>
