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
    if (isset($_POST['btnSubmit'])) {
        /*AFFECTATION DANS LES VARIABLES*/
        $prenoms = htmlspecialchars($_POST['prenoms']);
        $nom = htmlspecialchars($_POST['nom']);
        $num_tel = htmlspecialchars($_POST['num_tel']);
        $adr_mail = htmlspecialchars($_POST['adr_mail']);
        $password = sha1($_POST['pass']);
        $password2 = sha1($_POST['pass2']);
        if (!empty($_POST['prenoms']) AND !empty($_POST['nom']) AND !empty($_POST['num_tel']) AND !empty($_POST['adr_mail']) AND !empty($_POST['pass']) AND !empty($_POST['pass2'])) {
            if( filter_var($adr_mail, FILTER_VALIDATE_EMAIL)){
                if(is_numeric($num_tel)){
                    if (strlen($num_tel) == 8){
                        if ($password == $password2){
                            $rInsert = $pdo->prepare("UPDATE `utilisateurs` SET `prenoms_user` = ?, `nom_user` = ?, `numTel` = ?, `adr_mail` = ?, `mot_passe` = ? WHERE `num_user` = ?");
                            $done = $rInsert->execute(array($prenoms, $nom, $num_tel, $adr_mail, $password, $leNum));
                            if ($done) {
                                $msg = "Votre compte a été bien modifier!";
                                header("Location: index.php");
                                exit();
                            }else{
                                $erreur = "Erreur lors de la modification du compte!";
                            }      
                        }else{
                            $erreur = "Les mots de passes ne corrrespondent pas!";
                        }                            
                    }else{
                        $erreur = "Le numéro de téléphone n'est pas valide!";
                    }
                }else{
                    $erreur = "Le numéro de téléphone n'est pas valide!";
                }
            }else{
                $erreur= "Votre adresse e-mail n'est pas valide!";
            }
        }else{
            $erreur = "Veuillez remplir tous les champs!";
        }

    }
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Picos Coiffure - Mon profil</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="../css/account.css"/>
        <link rel="stylesheet" type="text/css" href="../css/profiluser.css"/>

    </head>
    <body>
       <!-- I N C L U S I O N -->
       <?php
       include("menu.php");
       ?>
       <div id="corpsSignIn">
        <h2>Mon profil</h2>
        <form id="fSignIn" action="" method="post" align="center">
            <div>
                <input type="text" required placeholder="Prénoms" maxlength="50" name="prenoms" class="input" value="<?php if(isset($prenoms)) echo $prenoms; ?>" title="Prénoms"/>
                <input type="text" required placeholder="Nom" maxlength="35" name="nom" class="input" value="<?php if(isset($nom)) echo $nom; ?>" title="Nom"/>
            </div>
            <input type="text" required placeholder="Numéro de téléphone(TOGO (+228))" maxlength="8" name="num_tel" class="input" id="numTel" title="Numéro de téléphone(TOGO (+228))" value="<?php if(isset($numero)) echo $numero; ?>"/>
            <div><input type="email" required placeholder="Adresse e-mail" maxlength="35" name="adr_mail" class="input" id="adrMail" title="Adresse e-mail" value="<?php if(isset($adresse)) echo $adresse; ?>"/></div>
            <div>
                <input type="password" required placeholder="Mot de passe" maxlength="20" name="pass" class="input" title="Mot de passe"/>
                <input type="password" required placeholder="Confirmer le mot de passe" maxlength="20" name="pass2" class="input" title="Confirmer le mot de passe"/>
            </div>
            <div class="blockEnLigne">
                <input type="reset" value="Annuler" id="btnCancel"/>
                <input type="submit" value="Modifier" id="btnSingIn" name="btnSubmit"/>
            </div>
        </form>
        <?php
        if(isset($erreur)){
            echo '<p style="color:red;" align="center" >'.$erreur.'</p>';
        }
        if(isset($msg)){
            echo '<p style="color:green;" align="center" >'.$msg.'</p>';
        }
        ?>
    </div> 
</body>
</html>