<?php

// Fonction de connexion à la BD 
function connect_bd($nomBd)
{
    $nomBd = 'nc231_flowtech';

    $nomServeur = 'nc231.myd.infomaniak.com';
    //nom du seveur
    $login = 'nc231_flowtech';
    //login de l'utilisateur
    $passWd = "Flowtech123";
    // mot de passe
    try {
        // Connexion à la BD et définition du jeu de caractères UTF-8
        $cnx = new PDO("mysql:host=$nomServeur; dbname=$nomBd", $login, $passWd);

        // PDO génére une erreur fatale si un problème survient. 
        $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Jeu de caractères
        $cnx->exec("SET CHARACTER SET utf8");

        return $cnx;

    } catch (PDOException $e) {

        print "Erreur!" . $e->getMessage() . "<br/>";
        die();
        return 0;
    }
}

function moyenneAge()
{
    // Connexion à la base de données
    $cnx = connect_bd('nc231_flowtech');

    // Vérifier la connexion
    if ($cnx) {
        // Préparation de la requête pour récupérer la date de naissance de tous les acteurs
        $req = $cnx->prepare("SELECT dateNaissance FROM Utilisateur");
        // Exécution de la requête
        $req->execute();
        // Récupération des dates de naissance
        $datesNaissance = $req->fetchAll(PDO::FETCH_COLUMN);

        // Calcul de l'âge pour chaque date de naissance
        $ages = [];
        foreach ($datesNaissance as $dateNaissance) {
            $age = date_diff(date_create($dateNaissance), date_create('today'))->y;
            $ages[] = $age;
        }

        // Calcul de la moyenne d'âge
        $moyenneAge = array_sum($ages) / count($ages);

        // Retourner la moyenne d'âge
        return $moyenneAge;
    } else {
        // Si erreur de connexion à la base de données
        return -1;
    }
}

// Fonction pour récupérer le nombre total de films sortis à partir de l'an 2000
function nbUtilisateurAnnee($anneeChoisit)
{
    // Connexion à la base de données
    $cnx = connect_bd('nc231_flowtech');

    // Vérifier la connexion
    if ($cnx) {
        // Préparation de la requête pour compter le nombre d'utilisateurs nés à partir de l'année choisie
        $req = $cnx->prepare("SELECT COUNT(*) as 'NbUtilisateur' FROM Utilisateur WHERE YEAR(dateNaissance) >= ?");
        // Liaison de la variable $anneeChoisit à la requête
        $req->bindParam(1, $anneeChoisit, PDO::PARAM_INT);
        // Exécution de la requête
        $req->execute();
        // Récupération du nombre d'utilisateurs
        $ligne = $req->fetch(PDO::FETCH_ASSOC);
        $nbUtilisateur = $ligne['NbUtilisateur'];
        // Retourner le nombre d'utilisateurs
        return $nbUtilisateur;
    } else {
        // Si erreur de connexion à la base de données
        return -1;
    }
}

// Fonction pour calculer le chiffre d'affaires total d'un type de PC
function chiffreAffairesTotal($nomArticle)
{
    // Connexion à la base de données
    $cnx = connect_bd('nc231_flowtech');

    // Vérification de la connexion
    if ($cnx) {
        try {
            // Requête pour récupérer le prix du PC
            $requete = "SELECT prix FROM Pc WHERE nomArticle = :nomArticle";
            $resultat = $cnx->prepare($requete);
            $resultat->bindParam(':nomArticle', $nomArticle, PDO::PARAM_STR);
            $resultat->execute();
            $prix = $resultat->fetch(PDO::FETCH_COLUMN);

            // Requête pour récupérer la quantité vendue
            $requete = "SELECT SUM(Quantite) AS total_quantite FROM AssociationPanier 
                        WHERE idPc IN (SELECT idPc FROM Pc WHERE nomArticle = :nomArticle)";
            $resultat = $cnx->prepare($requete);
            $resultat->bindParam(':nomArticle', $nomArticle, PDO::PARAM_STR);
            $resultat->execute();
            $total_quantite = $resultat->fetch(PDO::FETCH_COLUMN);

            // Calcul du chiffre d'affaires total
            $chiffre_affaires = $prix * $total_quantite;

            // Retourner le chiffre d'affaires total
            return $chiffre_affaires;

        } catch (PDOException $e) {
            // Gérer les erreurs éventuelles
            echo "Erreur : " . $e->getMessage();
            return 0;
        }
    } else {
        return 0; // Erreur de connexion à la base de données
    }
}


// Fonction pour lister tous les acteurs avec leur rôle dans les films
function listerUtilisateursAvecCommande()
{
    // Connexion à la base de données
    $cnx = connect_bd('nc231_flowtech');

    // Vérifier la connexion
    if ($cnx) {
        // Préparation de la requête pour récupérer les utilisateurs avec leur commande et quantité
        $req = $cnx->prepare("SELECT Pc.NomArticle, Utilisateur.Nom, Utilisateur.Prenom, AssociationPanier.quantite
                              FROM Utilisateur 
                              INNER JOIN Commande ON Utilisateur.idUtilisateur = Commande.idUtilisateur
                              INNER JOIN AssociationPanier ON Commande.idCommande = AssociationPanier.idCommande
                              INNER JOIN Pc ON AssociationPanier.idPc = Pc.idPc");
        // Exécution de la requête
        $req->execute();
        // Récupération des résultats
        $pcCommande = $req->fetchAll(PDO::FETCH_ASSOC);

        // Retourner les utilisateurs avec leur commande et quantité
        return $pcCommande;
    } else {
        // Si erreur de connexion à la base de données
        return [];
    }
}

// Fonction de deconnexion de la BD 
function deconnect_bd($nomBd)
{
    $nomBd = null;
}