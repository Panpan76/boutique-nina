<?php

namespace Controlleurs;

use Formulaires\Formulaire;
use Gestionnaires\Entite as GE;

use Exceptions\EntiteException;

class Produit extends Controlleur{

  /**
   * @Route('liste_produits', url="/produits")
   */
  public function listeProduits(){
    $geProduits = GE::getEntite('Entites\Produit');

    try{
      $produits = $geProduits->getAllBy();
    }catch(EntiteException $e){
      switch($e->getCode()){
        case EntiteException::AUCUN_RESULTAT:
          $produits = array();
          break;
      }
    }

    $this->render('produits/liste.php', 'Nos produits', array(
      'produits' => $produits
    ));
  }

  /**
   * @Route('liste_produits_admin', url="/admin/produits")
   * @requiert(Connecté)
   * @requiert(Role='Admin')
   */
  public function listeProduitsAdmin(){
    $geProduits = GE::getEntite('Entites\Produit');

    try{
      $produits = $geProduits->getAllBy();
    }catch(EntiteException $e){
      switch($e->getCode()){
        case EntiteException::AUCUN_RESULTAT:
          $produits = array();
          break;
      }
    }

    $this->render('produits/adminliste.php', 'Nos produits', array(
      'produits' => $produits
    ));
  }

  /**
   * @Route('ajout_produit', url="/admin/produits/ajout")
   */
  public function ajoutProduit(){
    $erreur = null;
    $form = new Formulaire('Entites\Produit');

    $geProduits = GE::getEntite('Entites\Produit');


    if(($produit = $form->getEntite()) !== null){
      try{
        $geProduits->sauvegarde($produit);
      }catch(EntiteException $e){
        var_dump($e);
      }
    }

    // On défini le message d'erreur
    $form->setErreur($erreur);

    // On définit les boutons
    $form->setBoutons(array('Sauvegarder'));

    $this->render('produits/ajout.php', 'Ajouter un produit', array(
      'formulaire'  => $form->getFormulaire()
    ));
  }

}
