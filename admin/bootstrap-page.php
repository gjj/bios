***REMOVED***
	define("BIOS_ADMIN", true);

	require_once '../includes/common.php';

	if (!isset($_SESSION['userid'])) {
		header("Location: .");
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

		<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
		<link href="assets/css/fontawesome-all.min.css" rel="stylesheet" />
		<link href="assets/css/bios.css" rel="stylesheet" />

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
                    <form id='bootstrap-form' action="admin/bootstrap-process" method="post" enctype="multipart/form-data">
					<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
						<h1 class="h2">Bootstrap</h1>
					</div>
					<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                            <h4>Bootstrap file:</h4>
                    </div>
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                            <h5><input id='bootstrap-file' type="file" name="bootstrap-file"><h5>
                    </div>
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                            <input type="submit"  name="submit" class="btn btn-dark" value="Import"></button>
                    </div>    
                    </form>
				</main>
			</div>
		</div>
	</body>
</html>
