<?php
// Ce fichier permet de tester les fonctions développées dans le fichier bdd.php (première partie)

// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
if (basename($_SERVER["PHP_SELF"]) == "users.php")
{
	header("Location:../index.php?view=users");
	die("");
}

include_once("libs/modele.php");
include_once("libs/maLibUtils.php"); // tprint

?>

<h1>Administration du site</h1>

<h2>Liste des utilisateurs de la base </h2>

<?php

echo "liste des utilisateurs autorises de la base :"; 
$users = listerUtilisateurs("nbl");
tprint($users);	// préférer un appel à mkTable($users);


// TODO : afficher les pseudos des utilisateurs autorisés 

for($i=0;$i<count($users);$i++){
	echo $users[$i]["pseudo"]
			. "<br />";
}

foreach($users as $nextUser) {
	echo $nextUser["pseudo"]
			. "<br />";
}

// foreach($tableau as $nextCase) // tab normal 
// foreach($tableau as $nextValeur)	// tab asso 
// foreach($tabAsso as $nextCle=>$nextVal) // tab asso 




echo "<hr />";
echo "liste des utilisateurs non autorises de la base :"; 
$users = listerUtilisateurs("bl");
tprint($users);	// préférer un appel à mkTable($users);

?>
<hr />
<h2>Changement de statut des utilisateurs</h2>

<!-- 
1. A quelle page sont envoyés les données du formulaire ?
=> page controleur.php

2. Quelles données sont envoyées ? 
=> idUser (menu déroulant)
=> action (par l'intermédiaire des deux boutons de soumission) 

Pourquoi y-a-t-il deux boutons submit ?
=> Pour le même menu déroulant (pour le même idUser)
on pourra demander deux actions différentes au controleur 
=> évite de créer deux menus déroulants différents 
-->

<form action="controleur.php">

<select name="idUser">
<?php
$users = listerUtilisateurs();


//TODO: ajouter le statut des utilisateurs à côté de leur nom 
// dans le menu déroulant

/*
<pre>
Array
(
    [0] => Array
        (
            [id] => 3
            [pseudo] => tom
            [passe] => ebm
            [blacklist] => 0
            [admin] => 1
            [couleur] => orange
        )

...
)
</pre>
*/

// préférer un appel à mkSelect("idUser",$users, ...)
foreach ($users as $dataUser)
{
	echo "<option value=\"$dataUser[id]\">\n";
	echo  $dataUser["pseudo"];
	if ($dataUser["blacklist"]) echo " (bl)"; 
	else  echo " (nbl)"; 
	echo "\n</option>\n"; 
}
?>
</select>

<input type="submit" name="action" value="Interdire" />
<input type="submit" name="action" value="Autoriser" />
</form>






