    <?php
    	session_start();
        $pdo = new PDO('mysql:host=localhost; dbname=salon', 'root', '');

        if (isset($_POST['btnSubmit'])) {
        	if (!empty($_POST['identification']) AND !empty($_POST['motPasse'])) {
        		$nom_admin = htmlspecialchars($_POST['identification']);
        		$password = htmlspecialchars($_POST['motPasse']);
        		$requetteAdmin = $pdo->prepare("SELECT * FROM `administration` where nom_admin = ? and pass_admin = ?");
        		$requetteAdmin->execute(array($nom_admin, $password));
        		$adminExiste = $requetteAdmin->rowCount();

        		if ($adminExiste == 1) {
        			$adminInfo = $requetteAdmin->fetch();
        			$_SESSION['num_admin'] = $adminInfo['num_admin'];
        			//$_SESSION['nom_admin'] = $adminInfo['nom_admin'];
        			header("Location: reservations.php");
                   

        		}else{
        			echo "<script>alert('Le nom d\'utilisateur ou le mot de passe est incorrect!');</script>";
        		}
        	}else{
        		echo "<script>alert('Veuilllez remplir tous les champs!');</script>";
        	}
        }
            
        	
    ?>

    <!DOCTYPE html>
    <html>
        <head>
            <title>Picos Coiffure - Administration</title>
            <meta charset="utf-8"/>
            <link rel="stylesheet" href="../css/index.css"/>
            <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        </head>
        <body>
            <div id="corpsSignUp">
                <h2>Picos Coiffure Administration</h2>
                <h4>Connectez-vous au compte administrateur</h4>
                <form method="POST" id="fSignUp" align="center" action="">
                     <input type="text" required placeholder="Nom d'utilisateur" maxlength="35" name="identification" class="input" value="<?php  if(isset($identification)) echo $identification ;?>" title="Nom d'utilisateur" />
                     <input type="password" required placeholder="Mot de passe" maxlength="20" name="motPasse" class="input" title="Mot de passe" />
                     <div>

                     </div>
                     <input type="submit" value="Se connecter" id="btnSingUp" name="btnSubmit"/>
                </form>
            </div>
        </body>
    </html>
