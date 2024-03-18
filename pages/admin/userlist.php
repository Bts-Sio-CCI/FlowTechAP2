<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Liste utilisateurs - FlowTech</title>
    <link rel="icon" type="image/x-icon" href="/img/logos/logo-min-rounded.png" />
    <!-- CSS CUSTOM + BOOTSTRAP -->
    <link href="/css/custom.css" rel="stylesheet" />
    <!-- BOOTSTRAP ICONS-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" />
</head>

<body class="bg-dark text-light">
    <?php include '../components/navbar.php'; ?>
    <?php
    require_once ('fonction.php');

    // Récupération de la valeur du genre sélectionné
    $genreId = isset ($_POST['sexe']) ? $_POST['sexe'] : null;

    // Établissement de la connexion
    $cnx = connect_bd('Utilisateur');
    // Traitement de l'inscription
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset ($_POST['inscription'])) {
        $Nom = $_POST['Nom'];
        $Prenom = $_POST['Prenom'];
        $email = $_POST['email'];
        $dateNaissance = $_POST['dateNaissance'];
        $Sexe = $_POST['Sexe'];
        $Adresse = $_POST['Adresse'];
        $login = $_POST['login'];
        $numTelephone = $_POST['numTelephone'];

        if ($cnx) {
            $result = $cnx->prepare("INSERT INTO Utilisateur (Nom, Prenom, email, dateNaissance, Sexe, Adresse, login, numTelephone) VALUES (:Nom, :Prenom, :email, :dateNaissance, :Sexe, :Adresse, :login, :numTelephone)");
            $result->bindParam(':Nom', $Nom, PDO::PARAM_STR);
            $result->bindParam(':Prenom', $Prenom, PDO::PARAM_STR);
            $result->bindParam(':email', $email, PDO::PARAM_STR);
            $result->bindParam(':dateNaissance', $dateNaissance, PDO::PARAM_STR);
            $result->bindParam(':Sexe', $Sexe, PDO::PARAM_INT);
            $result->bindParam(':Adresse', $Adresse, PDO::PARAM_STR);
            $result->bindParam(':login', $login, PDO::PARAM_STR);
            $result->bindParam(':numTelephone', $numTelephone, PDO::PARAM_STR);
            $result->execute();

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Erreur de connexion à la base de données.";
        }
    }

    if ($cnx) {
        if (isset ($_REQUEST['delete'])) {
            $result = $cnx->prepare("DELETE FROM Utilisateur WHERE idUtilisateur = :cle");
            $idUtilisateur = isset ($_REQUEST['cle']) ? $_REQUEST['cle'] : null;
            if ($idUtilisateur !== null) {
                $result->bindParam(':cle', $idUtilisateur, PDO::PARAM_INT);
                $result->execute();
            } else {
                echo "Erreur: idUtilisateur non spécifié.";
            }
        } elseif (isset ($_REQUEST['update'])) {
            $result = $cnx->prepare("UPDATE Utilisateur SET Nom=:Nom, Prenom=:Prenom, email=:email, dateNaissance=:dateNaissance, Sexe=:Sexe, Adresse=:Adresse, login=:login, numTelephone=:numTelephone WHERE idUtilisateur=:cle");
            $idUtilisateur = isset ($_REQUEST['cle']) ? $_REQUEST['cle'] : null;
            if ($idUtilisateur !== null) {
                $Nom = $_REQUEST['Nom'];
                $Prenom = $_REQUEST['Prenom'];
                $email = $_REQUEST['email'];
                $dateNaissance = $_REQUEST['dateNaissance'];
                $Sexe = $_REQUEST['Sexe'];
                $Adresse = $_REQUEST['Adresse'];
                $login = $_REQUEST['login'];
                $numTelephone = $_REQUEST['numTelephone'];

                $result->bindParam(':cle', $idUtilisateur, PDO::PARAM_INT);
                $result->bindParam(':Nom', $Nom, PDO::PARAM_STR);
                $result->bindParam(':Prenom', $Prenom, PDO::PARAM_STR);
                $result->bindParam(':email', $email, PDO::PARAM_STR);
                $result->bindParam(':dateNaissance', $dateNaissance, PDO::PARAM_STR);
                $result->bindParam(':Sexe', $Sexe, PDO::PARAM_INT);
                $result->bindParam(':Adresse', $Adresse, PDO::PARAM_STR);
                $result->bindParam(':login', $login, PDO::PARAM_STR);
                $result->bindParam(':numTelephone', $numTelephone, PDO::PARAM_STR);

                $result->execute();
            } else {
                echo "Erreur: idUtilisateur non spécifié.";
            }
        }
        // Debut container
        echo '<section class="container">';
        // Affichage du formulaire d'inscription
        echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
        ?>
        <div class="row mx-5 px-5 mt-3">
            <h4>Insérer un utilisateur</h4>

            <input type="text" name="Nom" placeholder="Nom" required class="mt-1">
            <input type="text" name="Prenom" placeholder="Prénom" required class="mt-1">
            <input type="email" name="email" placeholder="Email" required class="mt-1">
            <input type="date" name="dateNaissance" placeholder="Date de naissance" required class="mt-1">
            <select name="Sexe" required class="mt-1">
                <option value="0">Homme</option>
                <option value="1">Femme</option>
            </select>
            <input type="text" name="Adresse" placeholder="Adresse" required class="mt-1">
            <input type="text" name="login" placeholder="Pseudo" required class="mt-1">
            <input type="text" name="numTelephone" placeholder="Téléphone" required class="mt-1">
            <input type="submit" name="inscription" value="Inscription" class="mt-2 btn btn-flowtech btn-sm mt-1">
        </div>
        </form>

        <?php
        // Affichage des résultats
        $query = 'SELECT * FROM Utilisateur WHERE 1';

        if ($genreId !== null && $genreId !== '') {
            $query .= ' AND Sexe = :sexe';
        }

        $result = $cnx->prepare($query);

        if ($genreId !== null && $genreId !== '') {
            $result->bindParam(':sexe', $genreId, PDO::PARAM_INT);
        }

        $result->execute();


        // Affichage du formulaire de filtrage
        echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
        echo '<fieldset>';
        echo '<label for="sexe">Filtrer par sexe :</label>';
        echo '<select name="sexe">';
        echo '<option value="">Tous</option>'; // Option par défaut
        echo '<option value="0"' . ($genreId === '0' ? ' selected' : '') . '>Homme</option>';
        echo '<option value="1"' . ($genreId === '1' ? ' selected' : '') . '>Femme</option>';
        echo '</select>';
        echo '<input type="submit" value="Filtrer" class="btn btn-sm btn-flowtech" />';
        echo '</fieldset>';
        echo '</form>';

        echo "<div style='overflow: auto;'>";
        if ($result->rowCount() > 0) {
            echo "<table border='1'>";
            echo '<thead>';
            echo "<tr>";
            echo "<th>idUtilisateur</th>";
            echo "<th>Nom</th>";
            echo "<th>Prenom</th>";
            echo "<th>email</th>";
            echo "<th>dateNaissance</th>";
            echo "<th>Sexe</th>";
            echo "<th>Adresse</th>";
            echo "<th>Pseudo</th>";
            echo "<th>numTelephone</th>";
            echo "<th>Modifier</th>";
            echo "<th>Supprimer</th>";
            echo "</tr>";
            echo '</thead>';

            while ($donnees = $result->fetch()) {
                echo '<tbody>';
                echo "<form action=" . $_SERVER['PHP_SELF'] . " method='post'>";
                echo "<input type='hidden' name='cle' value='" . $donnees['idUtilisateur'] . "'>";
                echo "<tr>";
                echo "<td>" . $donnees['idUtilisateur'] . "</td>";
                echo "<td><input type='text' name='Nom'size='20' value='" . $donnees['Nom'] . "'></td>";
                echo "<td><input type='text' name='Prenom'size='20' value='" . $donnees['Prenom'] . "'></td>";
                echo "<td><input type='text' name='email'size='20' value='" . $donnees['email'] . "'></td>";
                echo "<td><input type='text' name='dateNaissance'size='20' value='" . $donnees['dateNaissance'] . "'></td>";
                echo "<td>";
                echo "<select name='Sexe'>";
                echo "<option value='0'" . ($donnees['Sexe'] == 0 ? ' selected' : '') . ">Homme</option>";
                echo "<option value='1'" . ($donnees['Sexe'] == 1 ? ' selected' : '') . ">Femme</option>";
                echo "</select>";
                echo "</td>";
                echo "<td><input type='text' name='Adresse'size='20' value='" . $donnees['Adresse'] . "'></td>";
                echo "<td><input type='text' name='login'size='20' value='" . $donnees['login'] . "'></td>";
                echo "<td><input type='text' name='numTelephone'size='20' value='" . $donnees['numTelephone'] . "'></td>";
                echo "<td><input type='submit' name='update' class='btn btn-primary btn-sm' value='Modifier'></td>";
                echo "<td><input type='submit' name='delete' class='btn btn-danger btn-sm' value='Supprimer'></td>";
                echo "</tr>";
                echo "</form>";
                echo '</tbody>';
            }
            echo "</table>";
            echo "</div>";


            // Affichage de la moyenne d'âge de tous les acteurs
            echo '<div class="my-2">';
            $moyenneAge = round(moyenneAge());
            echo "<label class='my-5'>Moyenne d'âge de tous les Utilisateur : $moyenneAge ans<label>";
            echo '</div>';
            echo '<div class="my-2">';
            echo '<form method="post" action="">';
            echo '<label for="anneeChoisit">Nombre d\'utilisateur née à partir de l\'année :</label>';
            echo '<input type="text" class="form-control" name="anneeChoisit" size="4" value="' . (isset ($_POST['anneeChoisit']) ? $_POST['anneeChoisit'] : '') . '">';
            echo '<input class="btn btn-flowtech btn-sm" type="submit" value="Calculer">';
            echo '</form>';
            echo '</div>';


            // fin container
            echo '<div>';
            // Vérifie si le formulaire a été soumis
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Vérifier si la clé 'anneeChoisit' est définie dans $_POST
                if (isset ($_POST['anneeChoisit'])) {
                    // Récupérer l'année choisie par l'utilisateur
                    $anneeChoisit = $_POST['anneeChoisit'];
                    // Appeler la fonction pour obtenir le nombre d'utilisateurs nés à partir de cette année
                    $nbUtilisateur = nbUtilisateurAnnee($anneeChoisit);
                    // Afficher le résultat
                    echo "Nombre d'utilisateurs nés à partir de l'année <b>$anneeChoisit</b>: $nbUtilisateur<br><br>";
                }
            }
            echo '</div>';


            $pcChoisi = isset ($_POST['pc']) ? $_POST['pc'] : 'Atlas'; // Par défaut, 'Atlas'
            $chiffreAffaires = chiffreAffairesTotal($pcChoisi);

            // Affichage du chiffre d'affaires total
    
            echo '<form method="post" action="">';
            echo '<label for="pc">Sélectionner le PC :</label>';
            echo '<select name="pc">';
            echo '<option value="Atlas">Atlas</option>';
            echo '<option value="Kraken">Kraken</option>';
            echo '<option value="Savana">Savana</option>';
            echo '<option value="Fractal-North">Fractal-North</option>';
            echo '<option value="Tracer">Tracer</option>';
            echo '<option value="Freezer">Freezer</option>';
            echo '<option value="Orion">Orion</option>';
            echo '<option value="Omega">Omega</option>';
            // Ajoutez d'autres options PC ici si nécessaire
            echo '</select>';
            echo '<input class="btn btn-flowtech btn-sm" type="submit" value="Afficher le chiffre d\'affaires">';
            echo '</form>';
            echo "Le chiffre d'affaires total pour le PC $pcChoisi est : $chiffreAffaires";


            // Affichage de tous les acteurs avec leur rôle
            $utilisateurAvecPc = listerUtilisateursAvecCommande();
            echo "<h2>Liste des utilisateurs avec leur commande et la quantité :</h2>";
            echo "<table class='table' border='1'>";
            echo "<tr><th>Pc</th><th>Utilisateur</th><th>Quantité</th></tr>";
            foreach ($utilisateurAvecPc as $pcCommande) {
                echo "<tr>";
                echo "<td>{$pcCommande['NomArticle']}</td>";
                echo "<td>{$pcCommande['Nom']} {$pcCommande['Prenom']}</td>";
                echo "<td>{$pcCommande['quantite']}</td>";
                echo "</tr>";
            }
            echo "</table>";

        } else {
            echo "Aucun enregistrement, désolé";
        }
        deconnect_bd('Utilisateur');
    }
    echo '</section>';
    ?>
    <?php include '../components/footer.php'; ?>
</body>

</html>