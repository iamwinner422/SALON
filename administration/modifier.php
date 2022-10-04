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

      $reqCoif = $pdo->prepare("SELECT * FROM `coiffeurs`");
  		$reqCoif->execute();
  		$nb = $reqCoif->rowCount();
    }
    if (isset($_GET['num_coif']) AND is_numeric($_GET['num_coif']) AND $_GET['num_coif'] <= $nb) {
      $num = intval($_GET['num_coif']);
      $reqCoif = $pdo->prepare("SELECT * FROM `coiffeurs` WHERE `num_coif` = ?");
      $reqCoif->execute(array($num));
      $listeCoif = $reqCoif->fetch();
      $nom = $listeCoif['nom_coif'];
      $num_tel = $listeCoif['num_tel'];
      $sexe = $listeCoif['sexe_coif'];
    }else{
      header("Location: coiffeurs.php");
    }



    if (isset($_POST['btnSubmit'])) {
        if (!empty($_POST['nom_coif']) AND !empty($_POST['num_tel'])) {
            $nom = htmlspecialchars($_POST['nom_coif']);
            $num_tel = htmlspecialchars($_POST['num_tel']);
            $sexe = $_POST['lstSexe'];
            if (strlen($num_tel) == 8 AND is_numeric($num_tel)) {
                /*VERIFICATION DE L'EXISTANCE DE L_ELEMENT*/
                    $req = $pdo->prepare("UPDATE `coiffeurs` SET `nom_coif` = ?, `num_tel` = ?, `sexe_coif` = ? WHERE `num_coif` = ?");
                    $done = $req->execute(array($nom, $num_tel, $sexe, $num));
                    if ($done) {
                        header("Location: coiffeurs.php");
                    }else{
                        $erreur = "Erreur!";
                    }
            }else{
                $erreur = "Le numéro de téléphone est invalide";
            }
        }else{
            $erreur = "Remplissez tous les champs";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Administration - Coiffeurs</title>
  <meta charset="utf-8"/>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="../css/admin.css"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
</head>
<body>
  <?php include("menu.php");?>
<center>
  <form id="ajoutCoif" method="post" action="" align="center" style="margin-top: 5%;">
      <h2 id="dialog-title">MODIFICATION</h2>
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
      <input type="submit" value="Modifier" id="btnAjoutCoif" name="btnSubmit"/>
      <?php
          if(isset($erreur)){
          echo '<p style="color:red;" align="center" id="erreur">'.$erreur.'</p>';
          }
      ?>
    </center>
  </form>
</body>
</html>
