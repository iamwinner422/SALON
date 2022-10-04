<?php
	$pdo = new PDO('mysql:host=localhost; dbname=salon', 'root', '');
	$leNum =intval($_GET['num_coif']);
	$rDelete = $pdo->prepare("DELETE FROM `coiffeurs` WHERE `num_coif` = ?");
	$done = $rDelete->execute(array($leNum));
	if ($done) {
		echo"<script>alert('Coiffeur supprimé avec succès!');</script>";
		header("Location: coiffeur.php");
		exit();
	}else{
		echo "<script>alert('Erreur!');</script>";
	}
?>
