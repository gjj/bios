<?php
	require_once 'includes/common.php';

	if (isLoggedIn()) {
		if (currentUserRole() == 1) {
			header("Location: admin/home");
		}
		else {
			header("Location: home");
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	<title>BIOS | Merlion University</title>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" crossorigin="anonymous" />
	<link rel="stylesheet" type="text/css" href="assets/css/bios.css" />
</head>
<body class="login">
	<section class="h-100">
		<div class="container h-100">
			<div class="row justify-content-md-center h-100">
				<div class="card-wrapper">
					<div class="brand">
						<img src="assets/images/logo-merlion-university.png" />
					</div>
					<div class="card fat">
						<div class="card-body">
							<h4 class="card-title">Login</h4>

							<?php
								if (isset($_SESSION['errors'])) {
							?>
							<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<?php
									printErrors();
								?>
								
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>

							<?php
								}
							?>

							<form method="POST" action="login">
								<div class="form-group">
									<label for="userId">User ID</label>
									<input id="userId" type="userId" class="form-control" name="userId" value="" required autofocus />
								</div>

								<div class="form-group">
									<label for="password">Password</label>
									<input id="password" type="password" class="form-control" name="password" required />
								</div>

								<div class="form-group m-0">
									<button type="submit" class="btn btn-primary btn-block">
										Login
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	
    <script type="text/javascript" src="assets/js/jquery.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="assets/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>
