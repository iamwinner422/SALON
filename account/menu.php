<div id="block">
	<nav id="menu">
		<ul>
			<li><a href="index.php">Accueil</a></li>
			<li><a href="add_reservation.php">Nouvelle reservation</a></li>
			<li><a href="reservations.php">Mes reservations</a></li>
			<?php
				if (isset($_SESSION['num']) AND $userInfo['num_user'] == $_SESSION['num']){
			?>
			<li class="dropdown" id="connect">
                 <a href="#" class="dropbtn"><?php echo $prenoms." ".$nom;?></a>
                 <div class="elementListe">
                     <a href="profil.php">Profil</a>
                     <a href="deconnexion.php">Deconnexion</a>
                 </div>
             </li>
			<?php
			}
			?>
		</ul>
	</nav>
</div>