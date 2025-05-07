<?php
    session_start();

	$_SESSION = array();
	$params   = session_get_cookie_params();
	$expired  = (isset($_GET["expired"])) ? "?expired=true" : ""; 

	setcookie(
		session_name(), '', time() - 42000,
		$params["path"],
		$params["domain"],
		$params["secure"],
		$params["httponly"]
	);

	session_destroy();
    header("Location: login$expired");
?>