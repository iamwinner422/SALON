<?php
    session_start();
	$pdo = new PDO('mysql:host=localhost; dbname=salon', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
	$pdoStat = $pdo->prepare('SELECT * FROM `type_service` order by prix');
	$pdoStat->execute();
	$liste = $pdoStat->fetchAll();

	//TRAITEMENT DU FORMULAIRE D'ENVOI DE MESSAGE
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Picos Coiffure</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="css/accueil.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
</head>
<body>
	<header class="entete">
		<div id="banniere">
			<h1>Picos Coiffure</h1>
			<h3>Salon de coiffure à lomé</h3>
        </div>
		<nav class="menu">
			<ul>
				<li><a href="#Accueil">Accueil</a></li>
				<li><a href="#Services">Nos services</a></li>
				<li><a href="#Contacts">Nous contacter</a></li>
				<li><a href="#About">A propos</a></li>
			</ul>
		</nav>	
	</header>
	<div class="corps">	
		<div id="couleur">																	
			<div id="commentaire" class="block">
				<p>
					Toutes les coiffures tendances du moment réunis en seul lieu.<br/>Reserver maintenant pour profiter de nombreuses offres
				</p><br/>
				<a href="account/add_reservation.php" id="btnReserver">Reserver maintenant</a>
			</div>
		</div>
		<div id="Accueil" class="block">
			<center><hr/></center>
			<p>Cher(e) visiteur(se), nous vous souhaitons la bienvenue sur note site. <br/>Nous espérons que vous trouverez tout ce dont vous avez besoin<br/> pour rester chick et propre pour vos différents rendez-vous.</p>
		</div>
		<div id="Services" class="block">
			<center><hr/></center>
			<h1 class="titre">NOS SERVICES</h1>
			<center>
			<div id="liste">
				<h2>Liste des services disponibles</h2>
				<table id="tble_liste" cellpadding="0" cellspacing="0" border="0">
					<tr id="entete-T">
						<td>Numéro</td>
						<td>Libellé</td>
						<td colspan="2">Prix</td>
					</tr>
					<?php
					$i = 1;
						foreach ($liste as $element) {
							echo "
							<tr id='infos'>
								<td>".$i."</td>
								<td>".$element['libelle']."</td>
								<td>".$element['prix']." FCFA</td>
								<td id='clien'><a href='account/add_reservation.php?type=".$element['num_type']."' id='lien'>Reserver</a></td>
							</tr>";
						$i++;
						}
					?>
				</table>
				
			</div>
			</center>
		</div>
		<div id="Contacts" class="block">
			<center><hr/></center>
			<h1 class="titre">NOUS CONTACTER</h1>
			<h3>Ecrivez nous sur notre E-mail</h3>
            <form method="POST"  action="" id="formContact">
                <div>
                    <input type="text" placeholder="Prénoms" maxlength="50" required name="prenoms" class="input"/>
                    <input type="text" placeholder="Nom" maxlength="35" required name="nom" class="input"/>
                </div>
                <div>
                    <input type="text" placeholder="Objet" maxlength="100" required name="objet" class="input"/>
                </div>
                <div>
                    <input type="tel" placeholder="Numéro de téléphone" maxlength="8" required name="number" class="input"/>
                    <input type="email" placeholder="Adresse E-mail" maxlength="50" required name="adresse" class="input"/>
                </div>
                <input type="text"  required placeholder="Votre message ici"  id="msgbox" name="message"/>
                </textarea>
                <br/>
                <input type="submit" name="envoyer" value="Envoyer" id="btnEnvoyer"/>
            </form>
            <?php
            	/*TRAITEMENT DU FORMULAIRE*/
            	if (isset($_POST['envoyer'])) {
            		/*CHAMPS VIDES*/
            		if (empty($_POST['prenoms']) AND empty($_POST['nom']) AND empty($_POST['objet']) and empty($_POST['number']) AND empty($_POST['adresse']) AND empty($_POST['message'])) {
            				$erreur = "Veuillez remplir tous les champs!";
            			
            		}else{
            			$nom = htmlspecialchars($_POST['nom']);
            			$prenoms = htmlspecialchars($_POST['prenoms']);
            			$objet = htmlspecialchars($_POST['objet']);
            			$numero = htmlspecialchars($_POST['number']);
            			$adrMail = htmlspecialchars($_POST['adresse']);
            			$message = htmlspecialchars($_POST['message']);

            			if (filter_var($adrMail, FILTER_VALIDATE_EMAIL)) {
            				if (is_numeric($numero)) {
            					if (strlen($numero) == 8) {
            						/*requette*/
            						$laRequete = $pdo->prepare("INSERT INTO `messages`(nom_emetteur, prenoms_emetteur, objet, num_Tel, adr_mail, message) VALUES(?,?,?,?,?,?)");
            						$laRequete->execute(array($nom, $prenoms, $objet, $numero, $adrMail, $message));
            						echo"<script>alert('Votre méssage a été bien envoyé. Nous vous répondrons dans les plus brefs délais.');</script";
            							/*affectation des valeurs nulles*/
            							$nom = " ";
            							$prenoms = " ";
            							$objet = " ";
            							$numero = " ";
            							$message = " ";
            							$adrMail = " ";

            					}else{
            						echo"<script>alert('Le numéro de téléphone saisi n\'est pas valide!');</script";
            					}
            				}else{
            					echo"<script>alert('Le numéro de téléphone saisi n\'est pas valide!');</script";
            				}
            			}else{
            				echo"<script>alert('L\'adresse e-mail saisie n\'est pas valide!');</script";
            			}
            		}
            	}
			?>
            <h3>OU</h3>
            <br/>
            <h3>Contactez nous directement</h3>
            <a href="tel://0022898023036" id="num">98023036</a>
            
		</div>
		<div id="About" class="block">
			<center><hr/></center>
			<h1 class="titre">A PROPOS DE NOUS</h1>
			<center>
			<div id="conteneur">
				<p>
					Nous sommes une équipe de jeunes dynamiques spécialisés dans plusieurs domaines dont la coiffure pour hommes et femmes.<br/>
					Nous sommes au parfums des nouvelles modes et tendances en mode masculine tant que féminine, et nous mieux placés pour vous donner des conseils pratiques.
				</p>
			</div>
			</center>
			<h3 style="color: #c83f1b; text-transform: uppercase; ">Picos Coiffure, 11 Rue Avenue Akei 152 Lomé - TOGO</h3>
		</div>
		<div id="Connexion" class="block">
		<center><hr/></center>
		<h1 class="titre">CONNECTEZ-VOUS</h1>
		<form method="POST" action="" id="fSignUp" align="center">
            <input type="text" required placeholder="Numéro de téléphone ou adresse e-mail" maxlength="35" name="identification" class="input" value="<?php  if(isset($identification)) echo $identification ;?>" />
            <input type="password" required placeholder="Mot de passe" maxlength="20" name="motPasse" class="input"/>
            <div>

            </div>
            <input type="submit" value="Se connecter" id="btnSingUp" name="btnSubmit"/>
        </form>
        <?php
        if (isset($_POST['btnSubmit'])) {
        $identification = htmlspecialchars($_POST['identification']);//VARIABLE UNIQUE D'IDENTIFICATION(E-MAIL ET NUMERO)
        $password = sha1($_POST['motPasse']);
        if (!empty($identification) AND !empty($password)) {
            if (is_numeric($identification))/*SI LA VARIABLE EST UN NUMERO DE TELEPHONE*/ {
                if (strlen($identification) == 8)/*VERIFICATION DE LA LONGUEUR*/ {
                    $requeteUser = $pdo->prepare("SELECT * FROM utilisateurs where numTel = ? AND mot_passe = ?");/*RECHERCHE DANS LA BASE*/
                    $requeteUser->execute(array($identification, $password));
                    $userExiste = $requeteUser->rowCount();

                    if ($userExiste == 1) {
                        $userInfo = $requeteUser->fetch();
                        $_SESSION['num'] = $userInfo['num_user'];
                        #NOM DU COOKIE __LINGOSER = LOGIN + USER
                        /*$_SESSION['nom'] = $userInfo['nom_user'];
                        $_SESSION['prenoms'] = $userInfo['prenoms_user'];
                        $_SESSION['numero'] = $userInfo['numTel'];
                        $_SESSION['adresse'] = $userInfo['adr_mail'];*/
                            echo '<script>document.location.replace("account/index.php");</script>';
                        
                    }
                    else{
                        echo "<script>alert('Le numéro de téléphone ou l\'adresse e-mail est incorrect!');</script>";
                    }
                }
                else{
                    echo "<script>alert('Le numéro de téléphone saisi n\'est pas valide!');</script>";
                }
            }
            elseif (filter_var($identification, FILTER_VALIDATE_EMAIL)) /*VERIFIE SI C'EST UNE EMAIL VALIDE*/{
                $requeteUser = $pdo->prepare("SELECT * FROM utilisateurs where adr_mail = ? AND mot_passe = ?");
                $requeteUser->execute(array($identification, $password));
                $userExiste = $requeteUser->rowCount();

                if ($userExiste == 1) {
                    $userInfo = $requeteUser->fetch();
                    $_SESSION['num'] = $userInfo['num_user'];
                    #NOM DU COOKIE __LINGOSER = LOGIN + USER
                   $done = setcookie('__lingosersal', $userInfo['num_user'], time() + 360*24*3600, null, null, false, true);
                    echo '<script>document.location.replace("account/index.php");</script>';
                                    }
                else{
                    echo "<script>alert('Le numéro de téléphone ou l\'adresse e-mail est incorrect!');</script>";
                }
            }
            else{
               echo "<script>alert('L\'adresse e-mail saisie n\'est pas valide!');</script>";
            }

        }
        else{
            echo "<script>alert('Veuillez remplir tous les champs!');</script>";
        }
    }

        ?>
        <p style="color: white; font-size: 18px;">Vous n'avez pas de compte? <a href="inscription.php" style="color: #c83f1b;">Inscrivez-vous!</a></p>
	</div>
	<div id="mentions">
		<p><a href="administration.php" style="text-decoration: none; color: white;"> Picos Coiffure</a> Powered by Picos Studio © 2020</p>
	</div>
	</div>
</body>
</html>