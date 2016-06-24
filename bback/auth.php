<?php
$user = 'admin'; $pass = 'zz22yy'; 
$auth_code = md5($user.'234ed$wd');
session_start();
if(isset($_GET['exit'])){
	unset($_SESSION['auth_code']);
}
if(!isAuth()){
	if(isset($_POST['auth_me']) && $_POST['user'] == ADMIN_LOGIN && $_POST['pass'] == ADMIN_PASS){
			$_SESSION['auth_code'] = $auth_code;
			header("Location: ".$_SERVER['PHP_SELF']);
	}else{
?>
<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
user:<input type="text" name="user"><br>
pass:<input type="password" name="pass"><br>
<input type="hidden" name="auth_me"><br>
<input type="submit" name="submit">
</form>
<?
	exit;
	}
}
function isAuth(){
	global $auth_code;
	if(@$_SESSION['auth_code'] == $auth_code) return true;
	return false;
}

// <a href="?exit=1">выход</a>
?>
