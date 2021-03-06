<?php
require("check.php");
if(!loggedin()){
	header("location:login.php");
}

$user = isset($_SESSION['login']) ? $_SESSION['login'] : "";

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>OTRS Tool</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Bootstrap -->
		<link href="assets/datepicker/css/datepicker.css" rel="stylesheet">
		<link href="assets/css/bootstrap.css" rel="stylesheet" media="screen">
		<style>
			body {
				padding-top: 60px;
				padding-bottom:40px;	
			}
			.sidebar-nav {
				padding: 9px 0;
			}
      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }
		</style>
		<link href="assets/css/bootstrap-responsive.css" rel="stylesheet" media="screen">
		<link href="assets/css/style.css" rel="stylesheet" media="screen">
		<script src="assets/js/jquery.js"></script>
		<script src="assets/js/bootstrap.js"></script>
		<script src="assets/datepicker/js/bootstrap-datepicker.js"></script>
	</head>
	<body>
				<!-- nav -->
				<div class="navbar navbar-fixed-top">
					<div class="navbar-inner">
						<div class="container">
							<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="brand" href="#">OTRS Tool</a>
							<div class="nav-collapse collapse">
								<p class="navbar-text pull-right">
									Logged in as <a href="#" class="navbar-link"><?=$user?></a>
									 <a href="logout.php"><i class="icon-off"></i></a>
								</p>
								<ul class="nav">
									<li class="divider-vertical"></li>
									<li><a href="#home"><i class="icon-home"></i> Dashboard</a></li>
									<li class="divider-vertical"></li>
									<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown">
											<i class="icon-wrench"></i> Editor <b class="caret"></b>
										</a>
											<ul class="dropdown-menu">
												<li><a href="?Action=ticketEditor">Ticket Editor</a></li>
												<li><a href="?Action=causeCodeEditor">Cause Code Tool</a></li>
											</ul>
									</li>
									<li class="divider-vertical"></li>
									<li><a href="?Action=monitoring"><i class="icon-eye-open"></i> Monitoring</a></li>
									<li class="divider-vertical"></li>
									<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown">
											<i class="icon-th-list"></i> Reports <b class="caret"></b>
										</a>
										<ul class="dropdown-menu">
											<li><a href=#weekly><i class="icon-tasks"></i> Weekly Report</a></li>
											<li><a href=#monthly><i class="icon-list"></i> Monthly</a></li>
										</ul>
									</li>
									<li class="divider-vertical"></li>
									<li><a href="#incident"><i class="icon-file"></i> Incident Report</a></li>
									<li class="divider-vertical"></li>
								</ul>
							</div><!--/.nav-collapse -->
						</div>
					</div>
				</div>
				<!-- end nav -->				

	<div class="container">
		<?php
			if(isset($_GET['Action'])){
				if(FILE_EXISTS("./pages/".$_GET['Action'].".php")){
					include("./pages/".$_GET['Action'].".php");
				}else{
					include("./pages/error.php");
				}
			}
		?>
	</div>

	</body>
</html>
