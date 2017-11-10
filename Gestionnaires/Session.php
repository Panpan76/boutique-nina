<?php

namespace Gestionnaires;


/**
 * Classe Session
 * Gère la session
 *
 * @author Panpan76
 * @date 10/11/2017
 */
class Session{
  const CLE = "FrameworkPHP"; // Une clé unique pour éviter que la session de cette application ne soit utilisée sur une autre application du serveur

  private $session; // La session

  private static $instance = null; // Instance singleton


  ##############
  ## Méthodes ##
  ##############

  /**
   * Permet d'avoir une seule instance de la classe
   *
   * @see __construct()
   *
   * @return Gestionnaires\Session
   */
  public static function getInstance(){
    if(is_null(self::$instance)){
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * Constructeur de la classe
   * Récupère la session et la unserialize
   */
  protected function __construct(){
    $this->session = array();
    if(isset($_SESSION[self::CLE])){
      $this->session = unserialize($_SESSION[self::CLE]);
    }
  }

  /**
   * Destructeur de la classe
   * Serialize la session et la sauvegarde
   */
  public function __destruct(){
    $session = null;
    if(isset($this->session)){
      $session = $this->session;
    }
    $_SESSION[self::CLE] = serialize($session);
  }

  /**
   * Définit une valeur
   * @param string  $cle    Clé/nom de la valeur
   * @param mixed   $valeur Valeur
   */
  public function set($cle, $valeur){
    $this->session[$cle] = $valeur;
  }

  /**
   * Ajout une valeur à un tableau
   * @param string  $cle    Clé/nom de la valeur
   * @param mixed   $valeur Valeur
   * @param boolean $debut  Indique si on ajoute au début où à la fin
   */
  public function add($cle, $valeur, $debut = false){
    if(!isset($this->session[$cle])){
      $this->session[$cle] = array();
    }
    if($debut){
      array_unshift($this->session[$cle], $valeur);
    }else{
      $this->session[$cle][] = $valeur;
    }
  }

  /**
   * Retourne la Session
   *
   * @return Gestionnaires\Session
   */
  public function getSession(){
    return $this->session;
  }

  /**
   * Définit l'utilisateur courant de l'application
   * @param Entites\Utilisateur $user Utilisateur courant
   */
  public function setUtilisateurCourant($user){
    $this->session['utilisateur'] = $user;
  }

  /**
   * Récupère l'utilisateur courant de l'application
   * @return Entites\Utilisateur
   */
  public function getUtilisateurCourant(){
    if(isset($this->session['utilisateur']) && !empty($this->session['utilisateur'])){
      return $this->session['utilisateur'];
    }
    return null;
  }

  /**
   * A la deconnexion, on supprime la session
   */
  public function deconnexion(){
    unset($this->session);
  }
}
