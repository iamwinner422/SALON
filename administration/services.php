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
  $lstType = $reqType->fetchAll();
  $nbType = $reqType->rowCount();

  $_SESSION['nb'] = $nbType; 
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
      <p class="entetes">Nombre de services diponibles</p>
      <p class="chiffres"><?php echo $nbType;?></p>
    </div>
  </div>
  <div id="serv">
    <h2>Liste des services disponibles</h2>

    <div id="btn">
      <main class="js-document">
        <button id="btnnew1"
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
        if (!empty($_POST['nom_serv']) AND !empty($_POST['lprix'])) {
          $llibelle = htmlspecialchars($_POST['nom_serv']);
          $lprice = htmlspecialchars($_POST['lprix']);

            if (is_numeric($lprice)) {
              /*VERIFICATION DE L'EXISTANCE DE L_ELEMENT*/
              $reqElt = $pdo->prepare("SELECT * FROM `type_service` WHERE `libelle` = ? AND `prix` = ?");
              $reqElt->execute(array($llibelle, $lprice));
              $elmExiste = $reqElt->rowCount();
              if ($elmExiste == 0) {
                $req = $pdo->prepare("INSERT INTO `type_service` VALUES (null, ?, ?)");
                $done = $req->execute(array($llibelle, $lprice));
                if ($done) {
                  header("Location: services.php");
                }else{
                  echo "<script>alert('Erreur!');</script>";
                }
              }else{
                  echo "<script>alert('Le service existe déjà!');</script>";
              }
            }else{
              echo "<script>alert('Le prix est incorrect!');</script>";
            }
        }else{
          echo "<script>alert('Veuillez remplir tous les champs!');</script>";
        }
      }
      ?>
      <form id="ajoutCoif" method="post" action="" align="center">
        <h2 id="dialog-title" style="color: #c83f1b; margin-top: 2%;">Nouveau Service</h2>
        <p id="dialog-desc"></p>
        <input type="text" required placeholder="Libellé du service" maxlength="255" name="nom_serv" class="input" value="<?php  if(isset($llibelle)) echo $llibelle ;?>" />
        <input type="text" required placeholder="Prix du service" maxlength="8" name="lprix" class="input" value="<?php  if(isset($lprice)) echo $lprice ;?>" />
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

    <center>
      <table id="tble_s" cellpadding="0" cellspacing="0" border="0" style="margin-top: 2%;">
        <tr class="entete">
          <td align="center">Numéro</td>
          <td>Libellé</td>
          <td align="center">Prix</td>
          <td colspan="2">Opération</td>
        </tr>
        <?php
        $i = 1;
        foreach ($lstType as $element) {
                  //REQUETE DU COIFFEUR
          echo "
          <tr class='info'>
          <td align='center'>".$i."</td>
          <td>".$element['libelle']."</td>
          <td align='center'>".$element['prix']." FCFA</td>
          <td class='clien' align='center'><a href='modif_type.php?num=".$element['num_type']."' id='llien'>Modifier</a></td>
          <td class='clien' align='center'><a href='spr_type.php?num=".$element['num_type']."' id='llien'>Supprimer</a></td>
          </tr>";
          $i++;
        }
        ?>
      </table>
    </center>
  </div>
  <script type="text/javascript" src="modal.js"></script>
</body>
</html>