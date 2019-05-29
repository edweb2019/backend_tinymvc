<?php
session_start();

	include_once "libs/maLibUtils.php";
	include_once "libs/maLibSQL.pdo.php";
	include_once "libs/maLibSecurisation.php"; 
	include_once "libs/modele.php"; 

	$qs = "";

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
				$qs = "?view=connexion";

				if ($login = valider("login"))
				if ($passe = valider("passe"))
				{
					// On verifie l'utilisateur, et on crée des variables de session si tout est OK
					// Cf. maLibSecurisation
					if (verifUser($login,$passe)) 
						$qs = "?view=accueil";
				}

				// On redirigera vers la page index automatiquement
			break;

			// TODO : implémenter la déconnexion 

			case 'logout' : 
			case 'Logout' : 
			case 'deconnexion' : 
				session_destroy();
				$qs = "?view=connexion";
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


			case 'Activer': 
				if ($idConv = valider("idConv")) {
					reactiverConversation($idConv);
				}
				$qs="?view=conversations&idLastConv=$idConv";
			break; 

			case 'Archiver': 
				if ($idConv = valider("idConv")) {
					archiverConversation($idConv);
				}
				$qs="?view=conversations&idLastConv=$idConv";
			break; 

			case 'Supprimer Conversation': 
				if ($idConv = valider("idConv")) {
					supprimerConversation($idConv);
				}
				$qs="?view=conversations";				
			break; 

			case 'Nouvelle Conversation': 
				$idConv = false;
				if ($theme = valider("theme")) {
					$idConv = creerConversation($theme);
				}
				$qs="?view=conversations&idLastConv=$idConv";		
			break;

			case 'Poster': 
				if($idConv = valider("idConv"))
				if($contenu = valider("contenu"))
				if($idAuteur = valider("idUser","SESSION"))
				{
					// TODO: "attaquer" la page chat 
					// pour ajouter des messages  "interdits" 
					// NEVER TRUST USER INPUT 
					// 1) Rien ne dit que les contraintes d'affichage 
					// du formulaire de création 
					// sont vérifiées quand on reçoit la requete ... 
					// Typiquement : la conversation peut etre archivée

					// 2) Rien n'empêche l'utilisateur de saisir du javascript
					// dans son message 
					// Faille XSS : Cross-Site-Scripting
					// Pour l'éviter : cf. fonction enregistrerMessage

					$dataConv = getConversation($idConv); 
					if ($dataConv["active"])
						enregistrerMessage($idConv, $idAuteur, $contenu);
				}
				// On revient à la vue chat POUR CETTE CONVERSATION 
				$qs="?view=chat&idConv=$idConv";		
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










