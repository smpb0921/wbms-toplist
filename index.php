<?php
	include("config/connection.php");
	include('scripts/login.php');
?>

<!doctype html>
<html style='background-color:#EAEFF2;'>
	<head>
		<title>Waybill Management System</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<style type="text/css">
			html, body{
				height:95%;
			}
			.section1{
				height:100%; 
				width:100%;
				display:table;
			}
			.section2{
				display: table-cell;
				vertical-align:middle;
			 }
			.section3{
				width: 350px;
				background: -webkit-linear-gradient(#F9FBFB, #E7EDF1);
			    background: -o-linear-gradient(#F9FBFB, #E7EDF1);
			    background: -moz-linear-gradient(#F9FBFB, #E7EDF1);
			    background: linear-gradient(#F9FBFB, #E7EDF1);
				margin: auto;
				
				border-top:2px solid #fff;
				border-left: 2px solid #fff;
				border: 1px solid #C1CDD4;
			}

			.section3-bottom{
				padding: 10px 0px;
				text-align: center;
				margin: auto;
				font-size: 10px;
				text-shadow: 1px 1px 0px rgba(255, 255, 255, 1);


			}

			.section3-inner{
				border-top:2px solid #fff;
				border-left: 2px solid #fff;
				overflow: auto;
				padding: 70px 20px 90px 20px;
				text-shadow: 1px 1px 0px rgb(200, 101, 101);
			}

			.img-responsive{
				margin: auto;
			}


			@media(max-width: 768px){
				.section3{
					width: 100%;
					height: 100%;
					background: #EAEFF2;
					border: none;
					margin: 0px;
				}
				.section3-inner{
					padding: 20px 10px 20px 10px;
					border: none;

				}
				html,body{
					height:98%;
				}
			}
		</style>

		<script type="text/javascript">
			var viewportWidth = screen.width;
			var path = document.URL.substr(0,document.URL.lastIndexOf('/'));
			/*if(viewportWidth<=479){
				window.location = path+"/mobile-app";

			}*/
		</script>

	</head>

	<body style='background-color:transparent;'>
		<div class="section1">
			<div class="section2">
				<div class="section3">
					<div class="section3-inner">
						<div class="col-sm-12">
							<img class='img-responsive' src="resources/logicorelogo.png">
							<br>
							<form action='index.php' method='post' class='form-horizontal'>
								<div class="form-group">
									<label for='username-input' class='control-label col-sm-3' style='color:#596D7C'>Username</label>
									<div class="col-sm-9">
										<input type='text' name='username' class='form-control' value="<?php if(isset($_SESSION['username'])){echo $_SESSION['username'];}?>">
									</div>
								</div>
								<div class="form-group">
									<label for='password-input' class='control-label col-sm-3' style='color:#596D7C'>Password</label>
									<div class="col-sm-9">
										<input type='password' name='password' id='password-input' class='form-control'>
									</div>
								</div>
								<input type='hidden' name='sometext' value="<?php echo $salt; ?>">
								<div class="form-group">
									<div class="col-sm-offset-3 col-sm-9">
										<button class='btn btn-default col-xs-12 mybtn btn-blue1' name='submitbtn' type='submit'>SIGN IN</button>
									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-offset-3 col-sm-9">
										<div id='errormsg' class='text-center'><?php echo $errormsg; ?></div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="section3-bottom">
					&copy; <?php echo date("Y"); ?> Turningpoint Inc. All Rights Reserved.
				</div>
			</div>
		</div>
		<script src="js/jquery-1.11.3.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script type="text/javascript">
			/*var height = $(document).height()+'px';
			$('html, body').css({height:'90%'});
			$('.container').css({ height:'100%', width:'100%',display:'table'});
			$('.row').css({display: 'table-cell',height:'100%',verticalAlign:'middle'});*/
		</script>
	</body>
</html>