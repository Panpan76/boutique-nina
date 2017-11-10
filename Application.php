<?php

use Gestionnaires\Annotation;
use Gestionnaires\GestionnaireRequetes;

/**
 * Classe Application
 * Gère l'application
 *
 * @author Panpan76
 * @date 08/11/2017
 */
final class Application{
  ###############
  ## Attributs ##
  ###############
  private static $instance; // Instance

  /**
   * Pattern singleton
   * Récupère l'instance de Application, si elle n'existe pas, la crée
   *
   * @return Application
   */
  public static function getInstance(){
    if(is_null(self::$instance)){
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * Constructeur de la classe Application
   * Soumis à un pattern singleton. Initialise les outils dont aura besoin l'application
   */
  private function __construct(){
    try{
      // Traitement de la requête en cours
      $gestionnaireRequetes = GestionnaireRequetes::getInstance();
      $gestionnaireRequetes->traiteRequete();
    }catch(RequeteException $e){
      switch($e->getCode()){
        case RequeteException::AUCUN_ROUTE_TROUVEE: // Si aucune route n'a été trouvée
          call_user_func_array(array(new \Controlleurs\Controlleur(), 'pageIntrouvable'), array());
          break;
      }
    }
  }

}
