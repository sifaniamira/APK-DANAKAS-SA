<?php
require_once('../koneksi/conn.php');
error_reporting(0);
session_start();
if(isset($_SESSION['user'])) {
  header('location: ../index.php');  
}

include '../helpers/Format.php';
$fm=new Format();

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache"); 
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 
header("Cache-Control: max-age=2592000");
$errors = array();
if(isset($_POST['login-submit'])) { 
	  $username = $fm->validation($_POST['username']);
	  $password = $fm->validation($_POST['password']);
      $user = $conn->real_escape_string($username);
      $pass = $conn->real_escape_string($password);

     if(empty($user) || empty($pass)) {
          if($user == "") {
            $errors[] = "Username Wajib di isi";
          } 
          if($pass == "") {
            $errors[] = "Password Wajib di isi";
          }
      }else {
      	$sql1 = $conn->query("SELECT username FROM tbl_user WHERE username = '$user'");
      	if ($sql1->num_rows > 0) {
      		$sql = $conn->query("SELECT password FROM tbl_user WHERE username = '$user'");
            $data = $sql->fetch_assoc();
            $hash = $data['password'];
            $pass1 = password_verify($pass,$hash);
             if($pass1){
                $sesi = $conn->query("SELECT * FROM tbl_user WHERE username='$user'");
                  $value = $sesi->fetch_assoc();
                  // set session
                  $_SESSION['user'] = $value['name'];
                  $_SESSION['id'] = $value['id'];
                  header('location: ../index.php');

                }else{
                   $errors[] = "Password Salah !";
                }
      	}else{
      		$errors[] = "Username tidak ditemukan !";
      	}

            

    }
} //tutup post
  
?>
<!DOCTYPE html>
<html lang="en">
<head>    
    <title>Dana Kas Kecil</title>
    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
<style>
body {
    padding-top: 90px;
    background-image: url(../assets/bkk.jpg);
    background-size: cover;
    background-repeat: no-repeat;
}
.panel-login {
	border-color: #fad000;
	-webkit-box-shadow: 0px 2px 3px 0px rgba(0,0,0,0.2);
	-moz-box-shadow: 0px 2px 3px 0px rgba(0,0,0,0.2);
	box-shadow: 0px 2px 3px 0px rgba(0,0,0,0.2);
}
.panel-login>.panel-heading {
	color: #fad000;
	background-color: #96c3eb;
	border-color: #fff;
	text-align:center;
}
.panel-login>.panel-heading a{
	text-decoration: none;
	color: ;
	font-weight: bold;
	font-size: 15px;
	-webkit-transition: all 0.1s linear;
	-moz-transition: all 0.1s linear;
	transition: all 0.1s linear;
}
.panel-login>.panel-heading a.active{
	color: #fad000;
	font-size: 18px;
}
.panel-login>.panel-heading hr{
	margin-top: 10px;
	margin-bottom: 0px;
	clear: both;
	border: 0;
	height: 1px;
	background-image: -webkit-linear-gradient(left,rgba(0, 0, 0, 0),rgba(0, 0, 0, 0.15),rgba(0, 0, 0, 0));
	background-image: -moz-linear-gradient(left,rgba(0,0,0,0),rgba(0,0,0,0.15),rgba(0,0,0,0));
	background-image: -ms-linear-gradient(left,rgba(0,0,0,0),rgba(0,0,0,0.15),rgba(0,0,0,0));
	background-image: -o-linear-gradient(left,rgba(0,0,0,0),rgba(0,0,0,0.15),rgba(0,0,0,0));
}
.panel-login input[type="text"],.panel-login input[type="password"] {
	height: 45px;
	border: 1px solid #ddd;
	font-size: 16px;
	-webkit-transition: all 0.1s linear;
	-moz-transition: all 0.1s linear;
	transition: all 0.1s linear;
}
.panel-login input:hover,
.panel-login input:focus {
	outline:none;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	box-shadow: none;
	border-color: #ccc;
}
.btn-login {
	background-color: #59B2E0;
	outline: none;
	color: #fad000;
	font-size: 14px;
	height: auto;
	font-weight: normal;
	padding: 14px 0;
	text-transform: uppercase;
	border-color: #59B2E6;
}
.btn-login:hover,
.btn-login:focus {
	color: #fff;
	background-color: #53A3CD;
	border-color: #53A3CD;
}


</style>
</head>

<body>
    <div class="container">
    	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-login">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-12">
								<a href="#" class="active" id="login-form-link">ADMIN LOGIN</a>
							</div>
							
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<?php if($errors) {
					                foreach ($errors as $key => $value) {
					                  echo '<div class="alert alert-danger" role="alert">
					                  <i class="glyphicon glyphicon-exclamation-sign"></i>
					                  '.$value.'</div>';                    
					                  }
					                } ?>
							</div>
							<div class="col-lg-12">
								<form id="login-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" role="form" style="display: block;">
									<div class="form-group">
										<input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="">
									</div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password">
									</div>
									<div class="form-group text-center">
										<input type="checkbox" tabindex="3" class="" name="remember" id="remember">
										<label for="remember"> Mau Aku Ingetin Terus Gak Nih ?</label>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="SILAHKAN MASUK ADMIN">
											</div>
										</div>
									</div>
								
								</form>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <!-- jQuery -->
    <script src="../assets/pixeladmin-lite/plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	    
</body>

</html>