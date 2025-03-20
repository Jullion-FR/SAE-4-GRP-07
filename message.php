<!DOCTYPE html>
<!-- page non temporaire ne doit pas etre accessible -->
<html>

<head>

	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/messagerie.css">

</head>

<body>
	<div class="container">
		<div class="left-column">
			<a href="index.php"><img class="logo" href="index.php" src="img/logo.png"></a>

			<p>Contacts récents :</p>
			<?php
			require 'fonction thomas/Messagerie/afficheContacts.php';
			?>
		</div>
		<div class="right-column">
		<?php include 'topbanner.php'; ?>
			<div class="surContenu">
				<div class="interlocuteur" <?php if (!isset($_GET['Id_Interlocuteur'])) {
												echo 'disabled';
											} ?>>
					<?php
					require "fonction thomas/Messagerie/afficherInterlocuteur.php";
					?>
				</div>
				<div class="contenuMessagerie">

					<?php
					require 'fonction thomas/Messagerie/afficheMessages.php';
					?>
					<form method="post" id="zoneDEnvoi">
						<input type="text" name="content" id="zoneDeTexte" <?php if ($formDisabled) {
																				echo 'disabled';
																			} ?>>
						<input type="submit" value="" id="boutonEnvoyerMessage" <?php if ($formDisabled) {
																					echo 'disabled';
																				} ?>>
					</form>
					<?php
					require 'fonction thomas/Messagerie/envoyerMessage.php';
					?>
				</div>
			</div>
		</div>
	</div>
</body>

</html>