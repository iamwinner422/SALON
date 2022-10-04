<div id="block">
	<nav id="menu">
		<ul>
			<li><a href="reservations.php"><img src="../img/reservation.png" alt="Reservations" title="Reservations" class="ico" /></a></li>
			<li><a href="services.php"><img src="../img/barber_chair.png" alt="Services" title="Services" class="ico" /></a></li>
			<li><a href="utilisateurs.php"><img src="../img/client.png" alt="Clients" title="Clients" class="ico"/></a></li>
			<li><a href="coiffeurs.php"><img src="../img/coiffeur.png" alt="Coiffeurs" title="Coiffeurs" class="ico"/></a></li>
			<?php
					if (isset($_SESSION['num_admin']) AND $leNum == $_SESSION['num_admin']){
			?>
			<li><a href="profil.php"><img src="../img/admin.png" alt="<?php echo $nomAdmin?>" title="<?php echo $nomAdmin?>" class="ico"/></a></li>
			<li><a href="deconnexion.php"><img src="../img/out.png" alt="Se deconnecter" title="Se deconnecter" class="ico"/></a></li>
			<?php
				}
			?>
		</ul>
	</nav>
</div>