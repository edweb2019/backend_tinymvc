<?php
// Ce fichier permet de tester les fonctions développées dans le fichier malibforms.php

// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
if (basename($_SERVER["PHP_SELF"]) == "conversations.php")
{
	header("Location:../index.php?view=conversations");
	die("");
}

include_once("libs/modele.php"); // listes
include_once("libs/maLibUtils.php");// tprint
include_once("libs/maLibForms.php");// mkTable, mkLiens, mkSelect ...



?>

<h1>Conversations du site</h1>

<!--
<h2>Liste des utilisateurs</h2>

<?php
	$users = listerUtilisateurs(); 
	//tprint($users);
	mkLiens($users,"pseudo","id","?view=users","idLastUser");
?>
-->

<h2>Liste des conversations actives</h2>

<?php
$conversations = listerConversations("actives");
//mkTable($conversations,array("id","theme")); 
//tprint($conversations);
mkLiens($conversations,"theme", "id", "?view=chat", "idConv"); 

// Comment n'afficher que id & thèmes ?
// A remplacer par mkLiens
?>

<h2>Liste des conversations inactives</h2>

<?php
$conversations = listerConversations("inactives");
mkTable($conversations,array("id","theme")); 
// A remplacer par mkLiens
?>

<hr />
<h2>Gestion des conversations</h2>

<?php

$conversations = listerConversations(); // toutes
// mkTable($conversations); // A remplacer par mkSelect

mkForm("controleur.php"); 

// Si on veut présélectionner une des conversation
// On passe son id dans le 5è argument de la fonction mkSelect
$idLastConv = valider("idLastConv"); 
mkSelect("idConv", $conversations,"id", "theme",$idLastConv);
mkInput("submit","action","Activer");
mkInput("submit","action","Archiver");
mkInput("submit","action","Supprimer Conversation");
endForm(); 

// TODO 1: Créer les fonctions nécessaires dans la couche modele
// TODO 2: Compléter le controleur pour appeler ces fonctions  
// TODO 3: Présélectionner la conversation qui vient d'être éditée dans le formulaire
echo "<br />";


// TODO 4: Ajouter un formulaire pour créer une conversation, le faire fonctionner 
mkForm("controleur.php"); 
mkInput("text","theme","");
mkInput("submit","action","Nouvelle Conversation");
endForm(); 

// TODO 5: Lors de la suppression d'une conversation, supprimer automatiquement TOUS LES MESSAGES de cette conversation

// Cf. création d'une relation d'intégrité référentielle
// dans le moteur de base de données 


?>















