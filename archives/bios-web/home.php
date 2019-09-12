<?php
	require_once("config/db.php");

	if (!isset($_SESSION['token'])) {
		header("Location: .");
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>BIOS | Merlion University</title>

	<!-- Custom fonts for this template-->
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

	<!-- Custom styles for this template-->
	<link href="assets/css/bios.css" rel="stylesheet">
	<link href="assets/css/bios-dashboard.css" rel="stylesheet">

	<link href="assets/css/fontawesome-all.min.css" rel="stylesheet">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css" />

	<style>
		.fc-view-container { 
		  overflow-x: scroll; 
		}
		.fc-view.fc-agendaDay-view.fc-agenda-view{
		  width: 500%;
		}
		/* **For 2 day view** */
		.fc-view.fc-agendaTwoDay-view.fc-agenda-view{
		  width: 500%;
		}
	</style>
</head>

<body>
	<div id="wrapper">

		<!-- Sidebar -->
		<ul class="navbar-nav bg-gray-900 sidebar sidebar-dark accordion">

			<!-- Sidebar - Brand -->
			<a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
				<div class="sidebar-brand-text mx-3">Merlion University BIOS</div>
			</a>

			<!-- Divider -->
			<hr class="sidebar-divider my-0">

			<li class="nav-item active">
				<a class="nav-link" href="">
					<span>Home</span>
				</a>
			</li>

			<hr class="sidebar-divider" />

			<div class="sidebar-heading">
				Interface
			</div>

			<li class="nav-item">
				<a class="nav-link" href="#">
					<span>Components</span>
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link" href="#">
					<span>Utilities</span>
				</a>
			</li>

		</ul>

		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
				<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
					<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
						<i class="fas fa-bars"></i>
					</button>
					
					<!-- Topbar Navbar -->
					<ul class="navbar-nav ml-auto">

						<li class="nav-item dropdown no-arrow d-sm-none">
							<a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-search fa-fw"></i>
							</a>
							<!-- Dropdown - Messages -->
							<div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
								<form class="form-inline mr-auto w-100 navbar-search">
									<div class="input-group">
										<input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
										<div class="input-group-append">
											<button class="btn btn-primary" type="button">
												<i class="fas fa-search fa-sm"></i>
											</button>
										</div>
									</div>
								</form>
							</div>
						</li>

						<div class="topbar-divider d-none d-sm-block"></div>

						<!-- Nav Item - User Information -->
						<li class="nav-item dropdown no-arrow">
							<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="mr-2 small">{{name}}</span>
							</a>
							<!-- Dropdown - User Information -->
							<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
								<a class="dropdown-item" href="#">
									Profile
								</a>
								<a class="dropdown-item" href="#">
									Settings
								</a>
								<a class="dropdown-item" href="#">
									Activity Log
								</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="logout">
									Logout
								</a>
							</div>
						</li>
					</ul>
				</nav>
				<div class="container-fluid">
					<div class="d-sm-flex align-items-center justify-content-between mb-4">
						<h1 class="h3 mb-0">Dashboard</h1>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div id="calendar"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Bootstrap core JavaScript-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/bootstrap.bundle.min.js"></script>

	<!-- Custom scripts for all pages-->
	<script src="assets/js/sb-admin-2.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js"></script>

	<script>
	    $(document).ready(function() {
	        // page is now ready, initialize the calendar...
	        $('#calendar').fullCalendar({
	            // put your options and callbacks here
	            defaultView: 'agendaWeek',
	            minTime: "08:00:00",
	            maxTime: "22:30:00",
	            contentHeight: 100,
	            firstDay: 1,
	            nowIndicator: true,
	            events: {
	            	url: 'https://tools.learn.helloholo.sg/api/v1/calendar',
	            	type: 'GET',
	            	data: {
	            		param: 1
	            	},
	            	textColor: 'white'
	            },
	            error: function() {
	            	alert('API error.');
	            },
	            header: {
					left: 'prev,next today myCustomButton',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},
				height: 'auto'
	        });
	    });
	</script>

</body>

</html>
