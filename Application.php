<?php

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
    // TODO Annotations
    // TODO Routes

    try{
      // TODO Traitement de la requête en cours
    }catch(\Exception $e){

    }
  }

}
