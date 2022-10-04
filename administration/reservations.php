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
    }
    //REQUETE DES RESERVATION
    $reqRes = $pdo->prepare("SELECT * FROM `reservations`");
    $reqRes->execute(array($leNum));
    $lstReserv = $reqRes->fetchAll();
    $nbRes = $reqRes->rowCount();
    //NOMBRE DE RESERVATION EN COURS
    $ladate = date('Y/m/d');
    $lheure = date('H:i');
    $reqEnCours = $pdo->prepare("SELECT * FROM `reservations` WHERE `date_reserv` > CURRENT_DATE AND `heure_reserv` <= CURRENT_TIME ORDER BY `num_reserv` DESC");
    $reqEnCours->execute();
    $lstReservEC = $reqEnCours->fetchAll();
    $nbResEnc = $reqEnCours->rowCount();
    //RESERVATION PASSEES
    $reqResP = $pdo->prepare("SELECT * FROM `reservations` WHERE `date_reserv` <= CURRENT_DATE AND `heure_reserv` <= CURRENT_TIME ORDER BY `num_reserv` DESC");
    $reqResP->execute();
    $lstReservP = $reqResP->fetchAll();
    $nbResP = $reqResP->rowCount();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Administration - Reservations</title>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="../css/admin.css"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
</head>
<body>
   <?php include("menu.php");?>
    <div id="alignement">
      <div class="box-info">
        <p class="entetes">Nombre de reservations en cours</p>
        <p class="chiffres"><?php echo $nbResEnc;?></p>
      </div>
      <div class="box-info">
        <p class="entetes">Nombre de reservations passées</p>
        <p class="chiffres"><?php echo $nbResP;?></p>
      </div>
    </div>
    <div id="liste1">
      <center>
            <h2>Liste des 5 dernières reservations en cours</h2>
            <table class="tble" cellpadding="0" cellspacing="0" border="0">
                <tr class="entete">
                    <td align="center">Numéro</td>
                    <td>Service</td>
                    <td>Prix</td>
                    <td>Date & Heure</td>
                    <td>Coiffeur</td>
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
                    <td>".$element['date_reserv']." à ".$element['heure_reserv']."</td>
                    <td>".$result1['nom_coif']."</td>";
                    $i++;
                }
                ?>
            </table>
          </center>
        </div>
        <br>
      <div id="liste2">
      <h2>Liste des dernières reservations passées</h2>
      <center>
        <table class="tble" cellpadding="0" cellspacing="0" border="0">
          <tr class="entete">
            <td align="center">Numéro</td>
            <td>Service</td>
            <td align="center">Prix</td>
            <td align="center">Date & Heure</td>
            <td>Coiffeur</td>
            <td colspan="2">Opération</td>
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
                  <tr id='infos'>
                  <td>".$i."</td>
                  <td>".$result2['libelle']."</td>
                  <td>".$result2['prix']." FCFA</td>
                  <td>".$element['date_reserv']." ".$element['heure_reserv']."</td>
                  <td>".$result1['nom_coif']."</td>
                  ";
                $i++;
              }
          ?>
          </table>
        </center>
        </div>
</body>
</html>