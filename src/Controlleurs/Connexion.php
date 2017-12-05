<?php

namespace Controlleurs;

use Formulaires\Formulaire;
use Gestionnaires\Session;
use Gestionnaires\Entite as GE;

use Exceptions\EntiteException;
use Exceptions\SecuriteException;

class Connexion extends Controlleur{

  /**
   * @Route('login', url="/login")
   * @requiert(Non connecté)
   */
  public function login(){
    $erreur = null;
    $form = new Formulaire('Entites\Utilisateur');

    $form->retire('nom');
    $form->retire('Role');


    if(($entite = $form->getEntite()) != null){
      // On récupère notre gestionnaire d'entité
      $ge = GE::getEntite('Entites\Utilisateur');

      // On essaie
      try{
        // On récupère l'utilisateur dont le nom est donné
        $utilisateur = $ge->getOneBy(array('login' => $entite->getLogin())); // Possibilité d'exception AUCUN_RESULTAT
        // Si le mot de passe ne corresond pas à celui en base
        if(!$utilisateur->compareMotDePasse($entite->getMotDePasse())){
          // On lance une exception de sécurité (mot de passe incorrect)
          throw new SecuriteException("Le mot de passe saisi ('{$entite->getMotDePasse()}') ne correspond pas à celui en base pour l`utilisateur '{$utilisateur->getNom()}'", SecuriteException::IDENTIFIANTS_INVALIDES);
        }
        // On est OK
        $session = Session::getInstance();
        $session->setUtilisateurCourant($utilisateur);

        // Redirection
        header('Location: '.genereLien('accueil'));

      }catch(EntiteException $e){
        if($e->getCode() == EntiteException::AUCUN_RESULTAT){
          $erreur = 'Identifiants invalides';
        }
      }catch(SecuriteException $e){
        if($e->getCode() == SecuriteException::IDENTIFIANTS_INVALIDES){
          $erreur = 'Identifiants invalides';
        }
      }
    }

    // On défini le message d'erreur
    $form->setErreur($erreur);

    // On définit les boutons
    $form->setBoutons(array('Connexion'));

    $this->render('connexion/login.php', 'Identification', array(
      'formulaire' => $form->getFormulaire()
    ));
  }


  /**
   * @Route('deconnexion', url="/deconnexion")
   * @requiert(Connecté)
   */
  public function deconnexion(){
    $session = Session::getInstance();
    $session->deconnexion();
    // Redirection
    header('Location: '.genereLien('accueil'));
  }
}
