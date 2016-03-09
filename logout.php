<?php
	session_start();
	if(session_destroy())
	{
		if(strpos($_SERVER['HTTP_REFERER'], "profile.php") !==false)
		{
			header('Location: index.php');
			exit;
		}
		else
		{
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			exit;
		}
	}
?>
