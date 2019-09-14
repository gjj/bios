<!DOCTYPE html>
<html lang="en">
	<head>
		<base href=".">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">

		<title><?php echo $viewData['title']; ?> | Merlion University</title>

		<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
		<link href="assets/css/fontawesome-all.min.css" rel="stylesheet" />
		<link href="assets/css/bios.css" rel="stylesheet" />

		<?php
			if (isset($viewData['styles'])) {
				echo $viewData['styles'];
			}
		?>
		
	</head>
	<body>
		<nav class="navbar navbar-expand-sm navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
			<a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">BIOS</a>

			<!--<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample03" aria-controls="navbarsExample03" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse d-xl-none" id="navbarsExample03">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item active">
						<a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Course Offerings</a>
					</li>
				</ul>
			</div>-->
			
			<ul class="navbar-nav px-3">
				<li class="nav-item text-nowrap">
					<a class="nav-link" href="logout">Sign out</a>
				</li>
			</ul>
		</nav>

		<?php
			include 'navbar.php';
		?>