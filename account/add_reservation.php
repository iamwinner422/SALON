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

    $ladate = date('d/m/Y');
    $ladate1 = date('Y-m-d');
    $lheure = date('H:i');
    $reqEnCours = $pdo->prepare("SELECT * FROM `reservations` WHERE `date_reserv` > CURRENT_DATE AND `heure_reserv` <= CURRENT_TIME AND `num_user` = ? ORDER BY `num_reserv` DESC");
    $reqEnCours->execute(array($leNum));
    $lstReservEC = $reqEnCours->fetchAll();
    $nbResEnc = $reqEnCours->rowCount();

        //RESERVATION PASSEES
    $reqResP = $pdo->prepare("SELECT * FROM `reservations` WHERE `date_reserv` <= CURRENT_DATE AND `heure_reserv` <= CURRENT_TIME  AND `num_user` = ? ORDER BY `num_reserv` DESC");
    $reqResP->execute(array($leNum));
    $lstReservP = $reqResP->fetchAll();
    $nbResP = $reqResP->rowCount();

        //REQUETE DES COIFFEURS
    try {
        $reqCoif = $pdo->prepare("SELECT `num_coif`, `nom_coif` FROM `coiffeurs` WHERE `disponible` = 'oui'");
        $reqCoif->execute();
        $lstCoif =$reqCoif->fetchAll();
    } catch (Exception $e) {
        echo $e;
    }

        //REQUTES DES SERVICES
    $reqServ = $pdo->prepare("SELECT * FROM `type_service`");
    $reqServ->execute();
    $listServ = $reqServ->fetchAll();
    $nbServ = $reqServ->rowCount();
        //SELECTION DU SERVICE DEPUIS L_ACCUEIL
    if (isset($_GET['type']) AND is_numeric($_GET['type']) AND $_GET['type'] <= $nbServ) {
        $num_ty = intval($_GET['type']);
        $requete = $pdo->prepare("SELECT `libelle` FROM `type_service` WHERE `num_type` = ?");
        $requete->execute(array($num_ty));
        $resul = $requete->fetch();
        $lelibelle = $resul['libelle'];
    }

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Mon compte - Reservations</title>
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
                    <p class="entetes">Reservations passées</p>
                    <p class="chiffres"><?php echo $nbResP;?></p>
                </div>
            </div>
        </div>
        <div id="add">
            <form method="POST" action="" id="formAdd" align="center">
                <center>
                <h2>Nouvelle Reservation</h2>
                <div>
                    <input type="date" style="color: #333;" min="<?php echo $ladate1;?>" name="date" value="<?php echo $ladate1;?>" class="input"/>
                    <input type="time" style="color: #333;" min="09:00" max="19:40" name="heure" value="<?php echo $lheure;?>" class="input"/>
                </div>
                <div>
                    <!--COIFFEUR-->
                    <div id="select">
                        <div class="lst-contenaire">
                            <?php
                            $selected = '';
                            // Parcours du tableau
                            echo '<select name="lstServ" class="lst">',"n";
                            echo '<optgroup label="Services">';
                            foreach($listServ as $service){
                            // Test de la valeur
                                if($lelibelle == $service['libelle']){
                                    $selected = 'selected="selected"';
                                }
                            // Affichage de la ligne
                                echo "\t",'<option value="', $service['num_type'] ,'"', $selected ,'>', $service['libelle']."     ".$service['prix']." FCFA" ,'</option>',"\n";
                            // Remise à zéro de $selected
                                $selected='';
                            }
                            echo '</optgroup>';
                            echo '</select>',"\n";
                            ?>
                        </div>
                        <div class="lst-contenaire">
                            <?php
                            $selected = '';
                            // Parcours du tableau
                            echo '<select name="lstCoif" class="lst">',"n";
                            echo '<optgroup label="Coiffeurs disponibles">';
                            foreach($lstCoif as $coiffeur){
                            //
                            // Affichage de la ligne
                                echo "\t",'<option value="', $coiffeur['num_coif'] ,'"', $selected ,'>', $coiffeur['nom_coif'] ,'</option>',"\n";
                            // Remise à zéro de $selected
                                $selected='';
                            }
                            echo '</<optgroup>';
                            echo '</select>',"\n";
                            ?>    
                        </div>
                    </div>
                </div>
                <input type="submit" name="reserver" value="Réserver" class="submit"/>
                </center>
            </form>

            <?php
                 /*---------INSERER RESERVATION------------*/

                if(isset($_POST['reserver'])){
                    $heure = $_POST['heure'];
                    $date = $_POST['date'];
                    $num_serv = $_POST['lstServ'];
                    $num_coif = $_POST['lstCoif'];

                    /*AJOUT D'UNE HEURE A LHEURE CHOISI*/
                    if (!empty($_POST['heure']) AND !empty($_POST['date'])) {
                        /*SELECTION DES RESERVATIONS*/
                        $reqSel = $pdo->prepare("SELECT * FROM `reservations` WHERE `date_reserv` = ? AND  `heure_reserv` = ? AND `num_coif` = ?");
                        $reqSel->execute(array($date, $heure, $num_coif));
                        $existe = $reqSel->rowCount();
                        if ($existe == 0) {
                           $reqInsert = $pdo->prepare("INSERT INTO `reservations` VALUES(null, ?, ?, ?, ?, ?)");
                            $done = $reqInsert->execute(array($date, $heure, $num_user, $num_coif, $num_serv));
                            if ($done) {
                                echo "<script>document.location.replace('index.php');</script>";

                            }else{
                                 $erreur = "Erreur!";
                            }
                        }else{
                             $erreur = "Vous ne pouvez pas ajouter cette reservation!";
                        }
                        
                    }else{
                        $erreur = "Veuillez remplir tous les champs";
                    }

                }


                if (isset($erreur)) {
                    echo "<p align='center' style='color:red;'>".$erreur."</p>";
                }
            ?>
        </div>
    </body>
    </html>