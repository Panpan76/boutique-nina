<?php

namespace Logguers;

use \Gestionnaires\Fichier;

use \Exceptions\FichierException;

/**
 * Classe Logguer
 * Gère les logs
 *
 * @author Panpan76
 * @date 08/11/2017
 */
class Logguer{
  ###############
  ## Attributs ##
  ###############
  const DOSSIER_LOG       = 'logs/'; // Emplacement des logs
  const REGEX_LOG_FORMAT  = "{date}\t{niveau}/{message}"; // Format du message

  protected static $instances = array(); // Une seule instance par fichier

  private $fichier; // Le fichier dans lequel on doit écrire les logs


  ##############
  ## Méthodes ##
  ##############

  /**
   * Pattern singleton multiple
   * Récupère l'instance de Logguer pour le fichier donné, si elle n'existe pas, la crée
   *
   * @param string $fichier Fichier dans lequel les logs devront être écrit
   * @return Logguers\Logguer
   */
  public static function getInstance($fichier = 'log'){
    if(!in_array($fichier, self::$instances)){
      self::$instances[$fichier] =  new self($fichier);
    }
    return self::$instances[$fichier];
  }

  /**
   * Constructeur de la classe Logguer
   * Soumis à un pattern singleton
   *
   * @param string $fichier Fichier dans lequel les logs seront écrit
   */
  protected function __construct($fichier){
    if(!is_dir(self::DOSSIER_LOG)){ // Si le dossier de log n'existe pas, on le crée
      mkdir(self::DOSSIER_LOG, 0777, true);
    }
    // On définit le nom final du fichier (avec la date pour avoir 1 fichier par jour)
    $fichier = self::DOSSIER_LOG.$fichier.'_'.date('Y_m_d').'.log';
    $this->fichier = $fichier;
  }


  /**
   * Ecrit un message dans les logs
   *
   * @param string $message Message a écrire
   * @param string $niveau  Niveau du message (Info, Warning, Error, Debug)
   */
  public function log($message, $niveau = 'I'){
    try{
      // Utilisation du gestionnaire de fichier
      $fichier = new Fichier($this->fichier);

      $temps = microtime(true); // On active les microsecondes
      $micro = sprintf("%06d", ($temps - floor($temps)) * 1000000);
      $date = new \DateTime(date('Y-m-d H:i:s.'.$micro, $temps)); // On récupère la date avec les microsecondes
      $date = $date->setTimeZone(new \DateTimeZone('Europe/Paris')); // On définit le timezone

      $str = self::REGEX_LOG_FORMAT; // On utilise le format défini en constante de classe

      // On remplace les infos
      $str = str_replace('{date}', $date->format('Y/m/d H:i:s.u'), $str);
      $str = str_replace('{niveau}', $niveau, $str);
      $str = str_replace('{message}', $message, $str);

      $fichier->ecrire($str); // On écrit
      $fichier->fermer(); // On ferme le fichier
    }catch(FichierException $e){
      echo $e;
    }

  }
}
