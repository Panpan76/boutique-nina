<?php

namespace Exceptions;

use \Logguers\Logguer;

/**
 * Classe Exception
 * Gère les exceptions du projet. Etend la classe générique Exception de PHP
 *
 * @author Panpan76
 * @date 08/11/2017
 */
class Exception extends \Exception{
  ###############
  ## Attributs ##
  ###############
  protected $titre;       // Le titre de l'exception
  protected $description; // La description
  protected $type;        // Le type (info, warning, danger)
  protected $code;        // Le code (propre au type d'exception)


  ##############
  ## Méthodes ##
  ##############

  /**
   * Constructeur de la classe
   *
   * @param string $titre       Titre de l'exception
   * @param string $description Description de l'exception
   * @param string $type        Type de l'exception
   * @param int    $code        Code de l'exception
   */
  public function __construct($titre = '', $description = '', $type = 'UNDEFINED', $code = 0){
    $this->titre        = $titre;
    $this->description  = $description;
    $this->type         = $type;
    $this->code         = $code;

    // On récupère la classe d'exception d'où provient l'exception
    $exceptionClasse = get_called_class();

    $log = Logguer::getInstance($exceptionClasse::FICHIER_LOG);
    $fichier  = $this->getFile(); // On récupère le fichier de l'appel à l'exception
    $ligne    = $this->getLine(); // On récupère la ligne de l'appel à l'exception
    $message  = "Exception [$this->titre] : $this->description ($fichier, ligne $ligne)"; // On prépare le message à logguer
    $log->log($message, $this->type);
  }

  /**
   * Permet d'afficher l'exception, avec sa trace
   *
   * @return string
   */
  public function __toString(){
    $str = "<div class='alert alert-danger' role='alert'>
              Exception capturée : <strong>{$this->titre}</strong><br />
              {$this->description}
            </div>";
    $str .= "<table class='table'>
              <tr>
                <th colspan='2'>Trace :</th>
              </tr>";

    // On récupère la trace depuis le début de l'execution du script jusqu'à l'appel à l'exception
    foreach($this->getTrace() as $trace){
      $params = array();
      foreach($trace['args'] as $param){
        if(is_array($param)){
          $params[] = 'array(...)';
        }elseif(is_object($param)){
          $params[] = 'Object<'.get_class($param).'>';
        }else{
          $params[] = "'$param'";
        }
      }
      $params = implode(', ', $params);
      $fichier = '';
      if(isset($trace['file'])){
        $fichier = "{$trace['file']} : {$trace['line']}";
      }
      $classe = '';
      if(isset($trace['class'])){
        $classe = "{$trace['class']}{$trace['type']}";
      }
      $str .= "<tr>
                <td>{$fichier}</td>
                <td>{$classe}{$trace['function']}({$params})</td>
              </tr>";
    }
    $str .= "</table>";
    return $str;
  }

  /**
   * Retourne la description de l'exception
   *
   * @return string
   */
  public function getDescription(){
    return $this->description;
  }
}
