<?php
	session_start();
	$pdo = new PDO('mysql:host=localhost; dbname=salon', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
	if (empty($_SESSION['num_admin'])) {
		header("Location: index.php");
		exit();	
	}else{
		$leNum = intval($_SESSION['num_admin']);
		$requetteAdmin = $pdo->prepare("SELECT * FROM `administration` where num_admin = ?");
		$requetteAdmin->execute(array($leNum));
		$adminInfo = $requetteAdmin->fetch();
		$nomAdmin = $adminInfo['nom_admin'];
		$password = $adminInfo['pass_admin'];

		$reqUser = $pdo->prepare("SELECT * FROM `utilisateurs` order by `num_user` DESC");
		$reqUser->execute();
		$listeUser = $reqUser->fetchAll();
		$nbUser = $reqUser->rowCount();
	}
?>
<!DOCTYPE html>
<html >
<head>
	<title>Administration -  Utilisateurs</title>
	<meta charset="utf-8"/>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="../css/admin.css"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
</head>
<body>
	<div id="corps">
		<div id="entete">
			<h3>Picos Coiffure - Centre d'Administration</h3>
		</div>
	
		<?php include("menu.php");?>
		<div id="alignement">
            <div class="box-info">
                <p class="entetes">Nombre des utilisateurs</p>
                <p class="chiffres"><?php echo $nbUser;?></p>
            </div>
        </div>
		<div id="users">
			<h4>Liste des Utilisateurs</h4>
			<center>
			<table class="tble" cellpadding="0" cellspacing="0" border="0">
				<tr class="entete">
					<td align="center">Numéro</td>
					<td>Nom & Prénoms</td>
					<td>Adresse E-mail</td>
					<td>Téléphone</td>
					<td align="center">Opération</td>
				</tr>
				<?php
					$i = 1;
					foreach ($listeUser as $user) {
						echo "
							<tr class='infos'>
								<td align='center'>".$i."</td>
								<td>".$user['nom_user']." ".$user['prenoms_user']."</td>
								<td>".$user['adr_mail']."</td>
								<td>".$user['numTel']."</td>
								<td align='center' class='clien'><a href='spr_user.php?num_user=".$user['num_user']."' class='supprimer'>Supprimer</a></td>
							</tr>";
						$i++;
					}	
				?>
			</table>
			</center>
		</div>		
	</div>
</body>
</html>