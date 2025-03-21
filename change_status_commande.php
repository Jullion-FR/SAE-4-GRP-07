<?php
include_once __DIR__ . "/loadenv.php";
?>
<?php

      $Id_Statut=htmlspecialchars($_POST["categorie"]);
      $Id_Commande=htmlspecialchars($_POST["idCommande"]);

      if ($Id_Statut==NULL){
        //rien ne se passe
        header('Location: delivery.php?');
      }
      else if ($Id_Statut==3){
        // annulation donc on rend les produits et le producteur ne voit plus la commande
        $db->query("UPDATE COMMANDE SET Id_Statut = ? WHERE Id_Commande = ?", 'ii', [$Id_Statut, $Id_Commande]);

        $returnQueryGetProduitCommande = $db->select('SELECT Id_Produit, Qte_Produit_Commande FROM produits_commandes  WHERE Id_Commande = ?', 'i', [$Id_Commande]);
        $iterateurProduit=0;
        $nbProduit=count($returnQueryGetProduitCommande);
        while ($iterateurProduit<$nbProduit){
          $Id_Produit=$returnQueryGetProduitCommande[$iterateurProduit]["Id_Produit"];
          $Qte_Produit_Commande=$returnQueryGetProduitCommande[$iterateurProduit]["Qte_Produit_Commande"];
          
          $db->query('UPDATE PRODUIT SET Qte_Produit = Qte_Produit + ? WHERE Id_Produit = ?', 'ii', [$Qte_Produit_Commande, $Id_Produit]);
          
          $iterateurProduit++;
        }
        //$bdd->query(('DELETE FROM CONTENU WHERE Id_Commande='.$Id_Commande.';'));
        //$bdd->query(('DELETE FROM COMMANDE WHERE Id_Commande='.$Id_Commande.';'));
      }
      else{
        //reste, on insert
        $db->query("UPDATE COMMANDE SET Id_Statut = ? WHERE Id_Commande = ?", 'ii', [$Id_Statut, $Id_Commande]);
        /*echo '<br>';
        echo $updateCommande;
        echo '<br>';
        echo $Id_Statut;
        echo '<br>';
        echo $Id_Commande;*/
      }
    header('Location: delivery.php?');
?>