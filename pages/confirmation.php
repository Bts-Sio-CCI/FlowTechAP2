<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Confirmation - FlowTech</title>
	<meta name="description" content="FlowTech, surement les meilleurs PC du marché!" />
	<link rel="icon" type="image/x-icon" href="../img/logos/logo-min-rounded.png" />
	<!-- CSS CUSTOM + BOOTSTRAP -->
	<link href="../css/custom.css" rel="stylesheet" />
	<!-- BOOTSTRAP ICONS-->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" />
</head>

<body class="bg-dark">
	<header class="header-shop">
		<!-- NAVBAR -->
		<?php include 'components/navbar.php'; ?>
		<div class="px-4 pt-5 my-5 text-center">
			<h1 class="display-4 fw-bold text-flowtech">Confirmations</h1>
			<div class="col-lg-6 mx-auto">
				<p class="lead mb-4 text-light">Merci d'avoir commandé!</p>
				<!-- BOUTON LIST ACHAT-->
				<section class="container my-5">
					<h2 class="fw-bold mb-4 text-light">Détails de la Commande</h2>
					<div class="table-responsive">
						<table class="table table-dark border-flowtech table-striped">
							<thead>
								<tr>
									<th>Article</th>
									<th>Prix</th>
									<th>Quantité</th>
								</tr>
							</thead>
							<tbody id="confirmation-tablebody">
								<!-- Le contenu du tableau de confirmation sera ajouté dynamiquement ici -->
							</tbody>
						</table>
					</div>
					<div class="d-flex justify-content-end">
						<p class="lead text-light">Sous-total: <span id="confirmation-subtotal"></span> €</p>
					</div>
				</section>
			</div>
		</div>
	</header>

	<!-- FOOTER -->
	<?php include '../pages/components/cookies.php'; ?>
	<?php include 'components/footer.php'; ?>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script src="../js/panier.js"></script>
	<script>
		document.addEventListener("DOMContentLoaded", function () {
			afficherDetailsCommande();
		});
	</script>
</body>

</html>