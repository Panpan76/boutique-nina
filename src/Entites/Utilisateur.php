<?php

namespace Entites;

use Interfaces\UtilisateurInterface;
use Gestionnaires\Entite as GE;

/**
 * @BDD(table='utilisateur')
 */
class Utilisateur extends EntiteMere implements UtilisateurInterface{
  /**
   * @BDD(champ='user_ID')
   * @BDD(type='int')
   * @BDD(key='PK')
   * @BDD(special='AI')
   */
  protected $id;

  /**
   * @BDD(champ='login')
   * @BDD(type='varchar(255)')
   * @BDD(unique)
   */
  protected $login;

  /**
   * @BDD(champ='nom')
   * @BDD(type='varchar(255)')
   */
  protected $nom;

  /**
   * @BDD(type='Entites\MotDePasse')
   * @BDD(crypt='sha256')
   */
  protected $motDePasse;

  /**
   * @BDD(champ='date_inscription')
   * @BDD(type='datetime')
   */
  protected $inscription;

  /**
   * @BDD(champ='role_ID')
   * @BDD(type='Entites\Role')
   * @BDD(nullable)
   */
  protected $role;

  /**
   * @BDD(postSelect)
   */
  public function initMdp(){
    // On récupère notre gestionnaire d'entité
    $ge = GE::getEntite('Entites\MotDePasse');
    $this->motDePasse = $ge->getOneBy(array('utilisateur' => $this->id), array('dateUtilisation' => 'DESC'));
  }


  public function getRoles(){
    return array($this->role);
  }

  public function getDroits(){
    return array();
  }

  public function compareMotDePasse($mdp){
    return strcmp($this->motDePasse->getMotDePasse(), hash('sha256', $mdp)) == 0;
  }
}
