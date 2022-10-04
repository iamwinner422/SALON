<?php
    $pdo = new PDO('mysql:host=localhost; dbname=salon', 'root', '');
    if (isset($_POST['btnSubmit'])) {
        /*AFFECTATION DANS LES VARIABLES*/
        $prenoms = htmlspecialchars($_POST['prenoms']);
        $nom = htmlspecialchars($_POST['nom']);
        $numero = htmlspecialchars($_POST['numTel']);
        $adrMail = htmlspecialchars($_POST['adrMail']);
        $password = sha1($_POST['motPasse']);
        $password2 = sha1($_POST['motPasse2']);

        /*VERIFICATION DES CHAMPS VIDES*/
       if (!empty($_POST['prenoms']) AND !empty($_POST['nom']) AND !empty($_POST['numTel']) AND !empty($_POST['adrMail']) AND !empty($_POST['motPasse']) AND !empty($_POST['motPasse2'])) {


            /*FILTRAGE DE L'ADRESSE MAIL*/
            if( filter_var($adrMail, FILTER_VALIDATE_EMAIL)){
                if(is_numeric($numero)){
                    if (strlen($numero) == 8){
                        /*VERIFICATION DE L'EXISTANCE DU NUMERO DE TELEPHONE -- DEBUT*/
                        $requeteNum = $pdo->prepare("SELECT * FROM utilisateurs WHERE numTel = ?");
                        $requeteNum->execute(array($numero));
                        $numExiste = $requeteNum->rowCount();/*FIN*/
                        if ($numExiste == 0) {
                            /*VERIFICATION DE L'EXISTANCE DE L'ADRESSE E-MAIL */
                            $requeteMail = $pdo->prepare("SELECT * FROM utilisateurs WHERE adrMail = ?");
                            $requeteMail->execute(array($adrMail));
                            $mailExiste = $requeteMail->rowCount();

                            if ($mailExiste == 0) {
                                /*COMPARAISON DES MOTS DE PASSES*/
                                if ($password == $password2){
                                  /*INSERTION DES DONNEES*/
                                    $requeteInscrip = $pdo->prepare("INSERT INTO utilisateurs(nom_user, prenoms_user, numTel, adr_mail, mot_passe) VALUES(?, ?, ?, ?, ?)");
                                    /*EXECUTION DE LA REQUETE*/
                                    $requeteInscrip -> execute(array($nom, $prenoms, $numero, $adrMail, $password));
                                    header("Location: index.php#Connexion");
                                    exit();
                                }
                                else{
                                    echo"<script>alert('Les mot de passe saisis ne correspondent pas!');</script>";
                                }
                            }
                            else{
                                echo"<script>alert('L\'adresse e-mail saisie est déjà utilisée!');</script>";
                            }
                        }
                        else{
                            echo"<script>alert('Le numéro de téléphone saisi est déjà utilisé!');</script>";
                        }
                    }
                    else{
                        echo"<script>alert('Le numéro de téléphone saisi n\'est pas valide!');</script>";
                    }
                }
                else{
                    echo"<script>alert('Le numéro de téléphone saisi n\'est pas valide!');</script>";
                }

            }
            else{
                echo"<script>alert('L\'adresse e-mail saisie n\'est pas valide!');</script>";
            }

       }
       else{
            echo "<script>alert('Veuillez remplir tous les champs!')</script>";
       }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Picos Coiffure - Insciption</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="css/inscrip.css"/>
    </head>
    <body>
        <header class="entete">
        <div id="banniere">
            <h1>Picos Coiffure</h1>
            <h3>Salon de coiffure à lomé</h3>
        </div>
        <nav class="menu">
            <ul>
                <li><a href="index.php#Accueil">Accueil</a></li>
                <li><a href="index.php#Services">Nos services</a></li>
                <li><a href="index.php#Contacts">Nous contacter</a></li>
                <li><a href="index.php#About">A propos</a></li>
            </ul>
        </nav>  
        </header>
        <div id="color">
        <div id="corpsSignIn">
            <h2>Picos Coiffure</h2>
            <h4>Créér votre compte Picos Coiffure</h4>
            <form id="fSignIn" action="" method="post" align="center">
                <div>
                    <input type="text" required placeholder="Prénoms" maxlength="50" name="prenoms" class="input" value="<?php if(isset($prenoms)) echo $prenoms ?>" title="Prénoms"/>
                    <input type="text" required placeholder="Nom" maxlength="35" name="nom" class="input" value="<?php if(isset($nom)) echo $nom ?>" title="Nom"/>
                </div>
                <input type="text" required placeholder="Numéro de téléphone(TOGO (+228))" maxlength="8" name="numTel" class="input" id="numTel" title="Numéro de téléphone(TOGO (+228))"/>
                <div><input type="email" required placeholder="Adresse e-mail" maxlength="35" name="adrMail" class="input" id="adrMail" title="Adresse e-mail"/></div>
                <div>
                    <input type="password" required placeholder="Mot de passe" maxlength="20" name="motPasse" class="input" title="Mot de passe"/>
                    <input type="password" required placeholder="Confirmer le mot de passe" maxlength="20" name="motPasse2" class="input" title="Confirmer le mot de passe"/>
                </div>
                <div class="blockEnLigne">
                    <input type="submit" value="S'inscrire" id="btnSingIn" name="btnSubmit"/>
                    <input type="reset" value="Annuler" id="btnCancel"/>
                </div>
            </form>
            <p><a href="index.php#Connexion">Se connecter à un compte existant</a></p>
        </div>
        <div id="mentions">
        <p>Picos Coiffure Powered by Picos Studio © 2020</p>
        </div>
        </div>
    </body>
</html>
