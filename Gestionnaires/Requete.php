<?php

namespace Gestionnaires;

use Gestionnaires\Session;

use Exceptions\SecuriteException;

/**
 * Classe Requete
 * Permet de gérer les requêtes soumises, en générant des exceptions en cas d'échec
 *
 * @author Panpan76
 * @date 10/11/2017
 */
class Requete{
  ###############
  ## Attributs ##
  ###############
  private static $requeteCourante; // La requête courante

  private $url;
  private $estEnvoyee;
  private $pagePrecedente;
  private $parametres;
  private $date;

  /**
   * Crée une nouvelle requête
   *
   * @param string $url        Url demandée
   * @param array  $parametres Données de la requêtes
   * @param string $precedent  Page précédente
   */
  public function __construct($url = null, $parametres = array(), $precedent = null){

    $this->url = is_null($url) ? '/'.$_GET['page'] : $url; // L'url
    if(isset($_GET['page'])){
      unset($_GET['page']);
    }
    $this->parametres = empty($parametres) ? array_merge($_POST, $_GET, $_FILES) : $parametres; // Les données
    $this->estEnvoyee = ($_SERVER['REQUEST_METHOD'] == 'POST') ? true : false; // Si un formulaire a été envoyé
    $this->pagePrecedente = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $precedent; // La page précédente

    $temps = microtime(true); // On active les microsecondes
    $micro = sprintf("%06d", ($temps - floor($temps)) * 1000000);
    $date = new \DateTime(date('Y-m-d H:i:s.'.$micro, $temps)); // On récupère la date avec les microsecondes
    $this->date = $date->setTimeZone(new \DateTimeZone('Europe/Paris')); // On définit le timezone

    $session = Session::getInstance();
    $session->add('requetes', $this, true);
    self::$requeteCourante = $this;
  }

  /**
   * Retourne l'url de la requête
   * @return string
   */
  public function getUrl(){
    return $this->url;
  }

  /**
   * Retourne la requête courante
   *
   * @return Gestionnaires\Requete
   */
  public static function getRequeteCourante(){
    return self::$requeteCourante;
  }

  /**
   * Retourne les paramètres de la requête
   *
   * @return array
   */
  public function getParametres(){
    return $this->parametres;
  }

  /**
   * Permet d'ajouter des paramètre à une requête
   *
   * @param  string $nom    Clé du paramètre
   * @param  mixed  $valeur Valeur du paramètre
   */
  public function ajoutParametre($nom, $valeur){
    $this->parametres[$nom] = $valeur;
  }

  /**
   * Indique si la requête a été envoyée ou non
   *
   * @return boolean
   */
  public function estEnvoyee(){
    return $this->estEnvoyee;
  }

  /**
   * Retourne la page précédente (url)
   *
   * @return string
   */
  public function getPagePrecedente(){
    return $this->pagePrecedente;
  }

  /**
   * Vérifie les droits pour accéder à cette requête
   * @param  array  $requiert Droits requis (connecté, rôles, droits)
   *
   * @return boolean
   */
  public function verifDroits($requiert = array()){
    if(!empty($requiert)){
      $session = Session::getInstance();
      // Si on doit être connecté et qu'on ne l'est pas
      if(isset($requiert['connecte']) && $requiert['connecte'] && is_null($session->getUtilisateurCourant())){
        throw new SecuriteException("L`utilisateur doit être connecté pour pouvoir accéder à la route '{$this->getUrl()}'", SecuriteException::ERREUR_NON_CONNEXION);
      }
      // Si on ne doit pas être connecté et qu'on l'est
      if(isset($requiert['connecte']) && $requiert['connecte'] == false && !is_null($session->getUtilisateurCourant())){
        throw new SecuriteException("L`utilisateur ne doit pas être connecté pour pouvoir accéder à la route '{$this->getUrl()}'", SecuriteException::ERREUR_CONNEXION);
      }

      // Si on doit avoir un rôle et qu'on ne l'a pas
      if(isset($requiert['roles'])){
        $aRole = false;
        foreach($session->getUtilisateurCourant()->getRoles() as $role){
          if(in_array($role->getNom(), $requiert['roles'])){
            $aRole = true;
            break;
          }
        }
        if(!$aRole){
          throw new SecuriteException("L`utilisateur {$session->getUtilisateurCourant()->getNom()} n`a pas les rôles requis pour pouvoir accéder à la route '{$this->getUrl()}'", SecuriteException::ERREUR_ROLES);
        }
      }

      // Si on doit avoir un droit et qu'on ne l'a pas
      if(isset($requiert['droits'])){
        $aDroit = false;
        foreach($session->getUtilisateurCourant()->getDroits() as $droit){
          if(in_array($droit->getNom(), $requiert['droits'])){
            $aDroit = true;
            break;
          }
        }
        if(!$aDroit){
          throw new SecuriteException("L`utilisateur {$session->getUtilisateurCourant()->getNom()} n`a pas les droits requis pour pouvoir accéder à la route '{$this->getUrl()}'", SecuriteException::ERREUR_DROITS);
        }
      }
    }
    return true;
  }

}
