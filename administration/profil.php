<?php
	session_start();
	$pdo = new PDO('mysql:host=localhost; dbname=salon', 'root', '');
	if (isset($_SESSION['num_admin'])) {
		$leNum = intval($_SESSION['num_admin']);
		$requeteAdmin = $pdo->prepare("SELECT * FROM `administration` WHERE `num_admin` = ?");
		$requeteAdmin->execute(array($_SESSION['num_admin']));
		$infosAdmin = $requeteAdmin->fetch();
		$nom_admin = $infosAdmin['nom_admin'];
		if (isset($_POST['btnSubmit'])) {
			if (!empty($_POST['username']) AND !empty($_POST['password']) AND !empty($_POST['password2'])) {
				$newname = htmlspecialchars($_POST['username']);
				$newpass1 = sha1($_POST['password']);
				$newpass2 = sha1($_POST['password2']);
				if ($newpass1 == $newpass2) {
					$reqUpdate = $pdo->prepare("UPDATE `administration` SET `pass_admin` = ?, `nom_admin` = ? WHERE `num_admin` = ?");
					$reqUpdate->execute(array($newpass1, $newname, $leNum));
					echo "<script>alert('Vos profil a été bien modifier!');</script>";
					header("location: index.php");
					exit();
				}else{
					echo "<script>alert('Les mots de passe saisies ne correspondent pas!');</script>";
				}
			}else{
				echo "<script>alert('Veuillez remplir tous les champs!');</script>";
			}
		}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Editer mon profil - Picos Coiffure</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" href="../css/index.css"/>
	<link rel="stylesheet" type="text/css" href="../css/admin.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
</head>
<body>
	<?php include("menu.php");?>
	<div id="corpsSignUp">
            <h2>Edition du profil</h2>
            <h4>Veuillez remplir tous les champ</h4>
            <form method="POST" id="fSignUp" align="center" action="">
                 <input type="text" required placeholder="Nom d'utilisateur" maxlength="35" name="username" class="input" value="<?php  if(isset($nom_admin)) echo $nom_admin ;?>" title="Nom d'utilisateur"/>
                 <input type="password" required placeholder="Mot de passe" maxlength="35" name="password" class="input" title="Mot de passe" />

                 <div>
                 	<input type="password" name="password2" placeholder="Confirmer le mot de passe" maxlength="35" required class="input" title="Confirmer le mot de passe" />
                 </div>
                 <input type="submit" value="Modifier" id="btnSingUp" name="btnSubmit"/>
            </form>
        </div>
</body>
</html>
<?php
}
?>