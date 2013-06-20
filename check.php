<?php
session_start();
require("pdo.php");

function loggedin(){
	$loggedin = isset($_SESSION['login']) ? true : false;
	return $loggedin;
}
function loggedout(){
	session_destroy();
	header("location:login.php");
}
function escape($string){
	return mysql_real_escape_string($string);
}


//login
if(isset($_POST['login'])){
	$username = escape($_POST['username']);
	$password = escape($_POST['password']);

	$hash = hash('sha256',$password);
	
	$sql = "SELECT login,pw FROM users WHERE login='$username' LIMIT 1";
	$count = rowCount($sql,$db);

	if($count!=0){	
		$user = fetch($sql,$db);
		if($user['pw']==$hash){
			$_SESSION['login'] = $user['login'];
			header("location:index.php");
		}else{
			$error = "<div class=\"alert alert-error\">Invalid password!</div>";	
		}
	}else{
		$error = "<div class=\"alert alert-error\">$username is invalid!</div>";	
	}


}

?>
