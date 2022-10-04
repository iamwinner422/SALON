<?php
	$pdo = new PDO('mysql:host=localhost; dbname=salon', 'root', '');
	$leNum =intval($_GET['num_reserv']);
	$rDelete = $pdo->prepare("DELETE FROM `reservations` WHERE `num_reserv` = ?");
	$done = $rDelete->execute(array($leNum));
	if ($done) {
		header("Location: index.php");
		exit();
	}else{
		header("Location: index.php");
		exit();
	}
?>
