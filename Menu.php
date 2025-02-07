<?php $page_courant = basename($_SERVER["PHP_SELF"]) ?>
<header>
		<?php

			if (!empty($id_agent)) {
			?>
			<!--Logo de l'application-->

			<a href="./">
			<img src="../Images/Logo.jpg">
			</a>
			
			<!--Bouton permettant de faire appaître ou dispaître les boutons de navigations-->	
			<input type="checkbox" name="BtnMenu" id="BtnMenu">
			<!--Label du bouton permettant de faire appaître ou dispaître les boutons de navigations-->
			<label for="BtnMenu">
					
			</label>
			<!--Liste contenant des boutons de navigation-->
			<ul id='BarreDeNavigation'>
				<li>
						<a href='gestion_produit.php' class='fas fa-user-cog' title="Gestion produit" <?php if($page_courant === "gestion_produit.php" OR $page_courant === "form_produit.php" OR $page_courant === "gestion_prod_rup.php" OR $page_courant === "Insert_produit.php" OR $page_courant  === "formulaire_commande.php"){echo "id='Active'";} ?>></a>
					</li>

					<li>
						<a href="gestion_paiement.php" class="fas fa-money-check-dollar" title="Gestion rapport journalier" <?php if($page_courant == "gestion_paiement.php"){echo "id='Active'";} ?>></a>
					</li>
						
					<li>
						<a href='gestion_commande.php' class='fas fa-shopping-bag' title="Gestion commande" <?php if($page_courant == "gestion_commande.php"){echo "id='Active'";} ?>></a>
					</li>

					<li>
						<a href='gestion_commande_en_livraison.php' class='fas fa-truck' title="gestion commande en cours de livraison" <?php if($page_courant == "gestion_commande_en_livraison.php" OR $page_courant == "Valider_commande.php"){echo "id='Active'";} ?>></a>
					</li>

					<li>
						<a href='gestion_client.php' class='fas fa-handshake' title="Gestion commande" <?php if($page_courant == "gestion_client.php"){echo "id='Active'";} ?>></a>
					</li>
			 
					<li>
						<a href="../FinSession.php" class="fas fa-sign-out" title="Déconnexion" ></a>
					</li>
				</ul>
			<?php
			}

			elseif (!empty($id_admin)) {
			?>
				<!--Logo de l'application-->
				<a href="./">
				<img src="../Images/Logo.jpg">
				</a>
			
				<!--Bouton permettant de faire appaître ou dispaître les boutons de navigations-->	
				<input type="checkbox" name="BtnMenu" id="BtnMenu">
				<!--Label du bouton permettant de faire appaître ou dispaître les boutons de navigations-->
				<label for="BtnMenu">
						
				</label>
				<ul>

					<li>
						<a href='gestion_utilisateur.php' class="fas fa-user-cog" title="ajouter un vendeur" <?php if($page_courant == "gestion_utilisateur.php"){echo "id='Active'";} ?>></a>
					</li>

					<li>
						<a href='form_insert_vendeur.php' class="fas fa-user-pen" title="ajouter un vendeur" <?php if($page_courant == "form_insert_vendeur.php"){echo "id='Active'";} ?>></a>
					</li>
			 
					<li>
						<a href="../FinSession.php" class="fas fa-sign-out" title="Déconnexion" ></a>
					</li>
				</ul>
			<?php
			}else{
			?>
				<!--Logo de l'application-->
				<img src="Images/Logo.jpg">
				<!--Bouton permettant de faire appaître ou dispaître les boutons de navigations-->	
				<input type="checkbox" name="BtnMenu" id="BtnMenu">
				<!--Label du bouton permettant de faire appaître ou dispaître les boutons de navigations-->
				<label for="BtnMenu">
						
				</label>
				<ul>
					<li >
						<a href='clients' class='fas fa-user' title='espace client'>
								
						</a>
					</li>
					<li >
						<a href='Vendeur' class='fas fa-store' title='espace vendeur'>
								
						</a>
					</li>

					<li >
						<a href='administrateur' class='fas fa-user-cog' title='gestion utilisateur'>
								
						</a>
					</li>
				</ul>
			<?php
			}
		?>
	</ul>
</header>


