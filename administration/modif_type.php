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

    //TYPE DE SERVICES
    $reqType = $pdo->prepare("SELECT * FROM `type_service`");
    $reqType->execute();
    $lstType = $reqType->fetch();
    $nbType = $reqType->rowCount();

    

    if (isset($_GET['num']) AND is_numeric($_GET['num']) AND $_GET['num'] <= $nbType) {
      $num = intval($_GET['num']);
      $req = $pdo->prepare("SELECT * FROM `type_service` WHERE `num_type` = ?");
      $req->execute(array($num));
      $liste = $req->fetch();
      $libelle = $liste['libelle'];
      $prix = $liste['prix'];
    }else{
      header("Location: services.php");
    }


    if (isset($_POST['btnSubmit'])) {
      if (!empty($_POST['lib']) AND !empty($_POST['price'])) {
        $lib = htmlspecialchars($_POST['lib']);
        $pri = htmlspecialchars($_POST['price']);
        if (is_numeric($pri)) {
          try {
              $requete = $pdo->prepare("UPDATE `type_service` SET `libelle` = ?, `prix` = ? WHERE `num_type` = ?");
              $done = $requete->execute(array($lib, $pri, $num));
            if ($done) {
              header("Location: services.php");
          }else{
            $erreur = "Erreur!";
          }
          } catch (Exception $e) {
            echo $e;
          }
        }else{
          $erreur = "Le prix saisi est incorrect!";
        }
      }
    }
?>
<html>
<head>
  <title>Administration - Services</title>
  <meta charset="utf-8"/>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="../css/admin.css"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
</head>
<body>
  <?php include("menu.php");?>
<center>
  <form id="ajoutCoif" method="post" action="" align="center" style="margin-top: 5%;">
      <h2 id="dialog-title">MODIFICATION D'UN SERVICE</h2>
      <p id="dialog-desc"></p>
      <input type="text" required placeholder="Nom du coiffeur" maxlength="255" name="lib" class="input" value="<?php  if(isset($libelle)) echo $libelle ;?>" />
      <input type="text" required placeholder="Numéro de téléphone" maxlength="8" name="price" class="input" value="<?php  if(isset($prix)) echo $prix ;?>" />
      <div id="select">
   </div>
      <input type="submit" value="Modifier" id="btnAjoutT" name="btnSubmit"/>
      <?php
          if(isset($erreur)){
          echo '<p style="color:red;" align="center" id="erreur">'.$erreur.'</p>';
          }
      ?>
    </center>
  </form>
</body>
</html>