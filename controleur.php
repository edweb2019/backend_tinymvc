<?php
session_start();

	include_once "libs/maLibUtils.php";
	include_once "libs/maLibSQL.pdo.php";
	include_once "libs/maLibSecurisation.php"; 
	include_once "libs/modele.php"; 

	$addArgs = "";

	if ($action = valider("action"))
	{
		ob_start ();

		echo "Action = '$action' <br />";

		// ATTENTION : le codage des caractères peut poser PB 
		// si on utilise des actions comportant des accents... 
		// A EVITER si on ne maitrise pas ce type de problématiques

		/* TODO: exercice 4
		// Dans tous les cas, il faut etre logue... 
		// Sauf si on veut se connecter (action == Connexion)

		if ($action != "Connexion") 
			securiser("login");
		*/

		// Un paramètre action a été soumis, on fait le boulot...
		switch($action)
		{
			
			// Connexion //////////////////////////////////////////////////


			case 'Connexion' :
				// On verifie la presence des champs login et passe
				if ($login = valider("login"))
				if ($passe = valider("passe"))
				{
					// On verifie l'utilisateur, et on crée des variables de session si tout est OK
					// Cf. maLibSecurisation
					verifUser($login,$passe); 	
				}

				// On redirigera vers la page index automatiquement
			break;


			case "Interdire": 

				// ATTENTION : ceci n'est pas une comparaison !!
				// Ce qui est fait : 
				 
				// 1) affectation de la variable $idUser
				// (valider() protège contre les injections SQL)
				// $idUser = valider("idUser"); 

				// 2) Test de la valeur de $idUser 
				// if ($idUser) { ...}

				if ($idUser = valider("idUser")) {
					// NB : dans les BDD, 
					// les id auto-générés commencent à 1 
					interdireUtilisateur($idUser);
				}

				// On demande à réafficher la vue 'users'
				// On en profite pour indiquer à la vue 
				// quel utilisateur présélectionner 
				$qs = "?view=users&idLastUser=$idUser";
				// NB : on est sûrs que la variable $idUser existe 
				// Car l'intérieur du if était une affectation !

			break; 

			case "Autoriser": 
				if ($idUser = valider("idUser")) {
						autoriserUtilisateur($idUser);
					}
				$qs = "?view=users&idLastUser=$idUser";

			break; 
		}

	}

	// On redirige toujours vers la page index, mais on ne connait pas le répertoire de base
	// On l'extrait donc du chemin du script courant : $_SERVER["PHP_SELF"]
	// Par exemple, si $_SERVER["PHP_SELF"] vaut /chat/data.php, dirname($_SERVER["PHP_SELF"]) contient /chat

	$urlBase = dirname($_SERVER["PHP_SELF"]) . "/index.php";
	// On redirige vers la page index avec les bons arguments

	header("Location:" . $urlBase . $qs);
	//qs doit contenir le symbole '?'

	// On écrit seulement après cette entête
	ob_end_flush();
	
?>










