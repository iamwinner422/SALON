<?php
	session_start();
    $pdo = new PDO('mysql:host=localhost; dbname=salon', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    if (empty($_SESSION['num_admin'])) {
      	header("Location: index.php");
      	exit();
  	}

  	if (isset($_GET['num']) AND is_numeric($_GET['num'])) {
  		
  		$num = $_GET['num'];
  		$rq = $pdo->prepare("DELETE FROM `type_service` WHERE `num_type` = ?");
  		$done = $rq->execute(array($num));
  		if($done){
  			header("Location: services.php");
  		}else{
  			echo "<script>alert('Erreur');</script>";
  		}
  	}else{
  		header('Location: services.php');
  	}
?>