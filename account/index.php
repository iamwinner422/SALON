<?php
	session_start();
	$pdo = new PDO('mysql:host=localhost; dbname=salon', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
	if (isset($_SESSION['num'])){
        $leNum = intval($_SESSION['num']);
        $requeteUser = $pdo->prepare("SELECT * FROM `utilisateurs` WHERE num_user = ?");
        $requeteUser->execute(array($leNum));
        $userInfo = $requeteUser->fetch();
        $num_user= $userInfo['num_user'];
        $nom =  $userInfo['nom_user'];
        $prenoms = $userInfo['prenoms_user'];
        $numero = $userInfo['numTel'];
        $adresse = $userInfo['adr_mail'];
    }else{
        header("Location: ../index.php#Connexion");
        exit();
    }
    //REQUETE DES RESERVATION
    $reqRes = $pdo->prepare("SELECT * FROM `reservations` WHERE `num_user` = ?");
    $reqRes->execute(array($leNum));
    $lstReserv = $reqRes->fetchAll();
    $nbRes = $reqRes->rowCount();
    //NOMBRE DE RESERVATION EN COURS
    $ladate = date('Y-m-d');
    $lheure = date('H:i:s');
    $reqEnCours = $pdo->prepare("SELECT * FROM `reservations` WHERE `date_reserv` > CURRENT_DATE AND `heure_reserv` <= CURRENT_TIME AND `num_user` = ? ORDER BY `num_reserv` DESC");
    $reqEnCours->execute(array($leNum));
    $lstReservEC = $reqEnCours->fetchAll();
    $nbResEnc = $reqEnCours->rowCount();

    //RESERVATION PASSEES
    $reqResP = $pdo->prepare("SELECT * FROM `reservations` WHERE `date_reserv` <= CURRENT_DATE AND `heure_reserv` <= CURRENT_TIME  AND `num_user` = ? ORDER BY `num_reserv` DESC");
    $reqResP->execute(array($leNum));
    $lstReservP = $reqResP->fetchAll();
    $nbResP = $reqResP->rowCount();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Mon compte - Picos Coiffure</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="../css/account.css"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
</head>
<body>
	<div id="affichage">
		<?php include('menu.php');?>
		<div id="alignement">
            <div class="box-info">
                <p class="entetes">Total de mes reservation</p>
                <p class="chiffres"><?php echo $nbRes;?></p>
            </div>
            <div class="box-info">
                <p class="entetes">Reservations en cours</p>
                <p class="chiffres"><?php echo $nbResEnc;?></p>
            </div>
            <div class="box-info">
                <p class="entetes">Reservations pass??es</p>
                <p class="chiffres"><?php echo $nbResP;?></p>
            </div>
        </div>
        <div id="liste1">
            <h2>Liste des 5 derni??res reservations en cours</h2>
            <table class="tble_liste" cellpadding="0" cellspacing="0" border="0">
                <tr class="entete-T">
                    <td align="center">Num??ro</td>
                    <td>Service</td>
                    <td>Prix</td>
                    <td>Date & Heure</td>
                    <td>Coiffeur</td>
                    <td colspan="2">Op??ration</td>
                </tr>
                <?php
                $i = 1;
                foreach ($lstReservEC as $element) {
                    //REQUETE DU COIFFEUR
                    $reqCoif = $pdo->prepare("SELECT `nom_coif` FROM `coiffeurs` WHERE `num_coif` = ?");
                    $reqCoif->execute(array($element['num_coif']));
                    $result1 = $reqCoif->fetch();
                    //REQUETE DU TYPE SERVICE
                    $reqType = $pdo->prepare("SELECT `libelle`, `prix` FROM `type_service` WHERE `num_type` = ?");
                    $reqType->execute(array($element['num_type_service']));
                    $result2 = $reqType->fetch();
                    echo "
                    <tr class='infos'>
                    <td align='center'>".$i."</td>
                    <td>".$result2['libelle']."</td>
                    <td>".$result2['prix']." FCFA</td>
                    <td>".$element['date_reserv']." ?? ".$element['heure_reserv']."</td>
                    <td>".$result1['nom_coif']."</td>
                    <td id='clien'><a href='spr.php?num_reserv=".$element['num_reserv']."' id='lien'>Annuler</a></td>
                    </tr>";
                    $i++;
                }
                ?>
            </table>
        </div>
        <br/>
        <div id="liste2">
            <h2>Liste des 5 derni??res reservations passe??s</h2>
            <table class="tble_liste" cellpadding="0" cellspacing="0" border="0">
                <tr class="entete-T">
                    <td align="center">Num??ro</td>
                    <td>Service</td>
                    <td>Prix</td>
                    <td>Date & Heure</td>
                    <td>Coiffeur</td>
                    <td colspan="2">Op??ration</td>
                </tr>
                 <?php
                    $i = 1;
                    foreach ($lstReservP as $element) {
                         //REQUETE DU COIFFEUR
                    $reqCoif = $pdo->prepare("SELECT `nom_coif` FROM `coiffeurs` WHERE `num_coif` = ?");
                    $reqCoif->execute(array($element['num_coif']));
                    $result1 = $reqCoif->fetch();
                    //REQUETE DU TYPE SERVICE
                    $reqType = $pdo->prepare("SELECT `libelle`, `prix` FROM `type_service` WHERE `num_type` = ?");
                    $reqType->execute(array($element['num_type_service']));
                    $result2 = $reqType->fetch();
                    echo "
                    <tr class='infos'>
                    <td align='center'>".$i."</td>
                    <td>".$result2['libelle']."</td>
                    <td>".$result2['prix']." FCFA</td>
                    <td>".$element['date_reserv']." ?? ".$element['heure_reserv']."</td>
                    <td>".$result1['nom_coif']."</td>
                    <td id='clien'><a href='spr.php?num_reserv=".$element['num_reserv']."' id='lien'>Supprimer</a></td>
                    </tr>";
                    $i++;
                    }
                ?>
            </table>
        </div>
	</div>
</body>
</html>