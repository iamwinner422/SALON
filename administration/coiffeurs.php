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

		$reqCoif = $pdo->prepare("SELECT * FROM `coiffeurs` order by `num_coif` DESC");
		$reqCoif->execute();
		$listeCoif = $reqCoif->fetchAll();
		$nbCoif = $reqCoif->rowCount();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Administration -  Coiffeurs</title>
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
                <p class="entetes">Nombre des coiffeurs</p>
                <p class="chiffres"><?php echo $nbCoif;?></p>
            </div>
        </div>
		<div id="users">
			<h4>Liste des coiffeurs</h4>
			<center>
			<table class="tble" cellpadding="0" cellspacing="0" border="0">
				<tr class="entete">
					<td align="center">Numéro</td>
					<td>Nom </td>
					<td>Téléphone</td>
					<td>Sexe</td>
					<td align="center" colspan="2">Opération</td>
				</tr>
				<?php
					$i = 1;
					foreach ($listeCoif as $coif) {
						echo "
							<tr class='infos'>
								<td align='center'>".$i."</td>
								<td>".$coif['nom_coif']."</td>
								<td>".$coif['num_tel']."</td>
								<td>".$coif['sexe_coif']."</td>
								<td align='center' class='clien'><a href='modifier.php?num_coif=".$coif['num_coif']."' class='supprimer'>Modifier</a></td>
								<td align='center' class='clien'><a href='spr_coif.php?num_coif=".$coif['num_coif']."' class='supprimer'>Supprimer</a></td>
							</tr>";
						$i++;
					}
				?>
			</table>
			</center>
			<div id="btn">
			<main class="js-document">
            <button id="btnnew"
                class="new"
                type="button"
                aria-haspopup="dialog"
                aria-controls="dialog">Nouveau
            </button>
        </main>
        <center>
        <div
            id="dialog"
            role="dialog"
            aria-labelledby="dialog-title"
            aria-describedby="dialog-desc"
            aria-modal="true"
            aria-hidden="true"
            tabindex="-1"
            class="c-dialog">
            <div role="document" class="c-dialog_box">
                <button
                    type="button"
                    aria-label="Fermer"
                    title="Fermer"
                    data-dismiss="dialog">x
                </button>
                <!--FORMULAIRE D'AJOUT-->
                <?php
                    if (isset($_POST['btnSubmit'])) {
                        if (!empty($_POST['nom_coif']) AND !empty($_POST['num_tel'])) {
                            $nom = htmlspecialchars($_POST['nom_coif']);
                            $num_tel = htmlspecialchars($_POST['num_tel']);
							$sexe = $_POST['lstSexe'];
                            if (strlen($num_tel) == 8 AND is_numeric($num_tel)) {
                                /*VERIFICATION DE L'EXISTANCE DE L_ELEMENT*/
                                $reqElt = $pdo->prepare("SELECT * FROM `coiffeurs` WHERE `nom_coif` = ? AND `num_tel` = ?");
                                $reqElt->execute(array($nom, $num_tel));
                                $elmExiste = $reqElt->rowCount();
                                $disponible = "oui";
                                if ($elmExiste == 0) {
                                    $req = $pdo->prepare("INSERT INTO `coiffeurs` VALUES (null, ?, ?, ?, ?)");
                                    $done = $req->execute(array($nom, $num_tel, $sexe, $disponible));
                                    if ($done) {
                                        header("Location: coiffeurs.php");
                                    }else{
                                        $erreur = "Erreur!";
                                    }
                                }else{
                                    $erreur = "Le coiffeur existe déjà!";
                                }
                            }else{
                                $erreur = "Le numéro de téléphone est invalide";
                            }
                        }else{
                            $erreur = "Remplissez tous les champs";
                        }
                    }
                ?>
                <form id="ajoutCoif" method="post" action="">
                    <h2 id="dialog-title">Nouveau Coiffeur</h2>
                    <p id="dialog-desc"></p>
                    <input type="text" required placeholder="Nom du coiffeur" maxlength="255" name="nom_coif" class="input" value="<?php  if(isset($nom)) echo $nom ;?>" />
                    <input type="text" required placeholder="Numéro de téléphone" maxlength="8" name="num_tel" class="input" value="<?php  if(isset($num_tel)) echo $num_tel ;?>" />
                    <div id="select">
                		<div class="lst-contenaire">
		                    <select name="lstSexe" class="lst">
		                    <optgroup label="Sexe">
		                    	<option value="Masculin">Masculin</option>
		                    	<option value="Féminin">Féminin</option>
		                    </optgroup>
		                    </select>
		                </div>
		             </div>
                    <input type="submit" value="Ajouter" id="btnAjoutCoif" name="btnSubmit"/>
                    <?php
                        if(isset($erreur)){
                        echo '<p style="color:red;" align="center" id="erreur">'.$erreur.'</p>';
                        }
                    ?>
                </form>
            </div>
        </div>
			</div>
		</div>
	</div>
	 <script type="text/javascript" src="modal.js"></script>
</body>
</html>
