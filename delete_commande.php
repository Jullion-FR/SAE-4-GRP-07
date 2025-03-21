<?php
include_once __DIR__ . "/loadenv.php";
?>
<?php


$Id_Commande = htmlspecialchars($_POST["deleteValeur"]);

$returnQueryGetProduitCommande = $db->select('SELECT Id_Produit, Qte_Produit_Commande FROM produits_commandes  WHERE Id_Commande = ?', 'i', [$Id_Commande]);
$iterateurProduit = 0;
$nbProduit = count($returnQueryGetProduitCommande);
while ($iterateurProduit < $nbProduit) {
  $Id_Produit = $returnQueryGetProduitCommande[$iterateurProduit]["Id_Produit"];
  $Qte_Produit_Commande = $returnQueryGetProduitCommande[$iterateurProduit]["Qte_Produit_Commande"];

  $db->query('UPDATE PRODUIT SET Qte_Produit = Qte_Produit + ? WHERE Id_Produit = ?', 'ii', [$Qte_Produit_Commande, $Id_Produit]);

  $iterateurProduit++;
}
$db->query('UPDATE COMMANDE SET Id_Statut = 3 WHERE Id_Commande = ?', 'i', [$Id_Commande]);

header('Location: achats.php?');
?>