<?php

namespace Exceptions;

class BaseDeDonneesException extends Exception{
  const FICHIER_LOG = 'BaseDeDonnees';

  const ERREUR_CONNEXION        = 0;
  const TABLE_INEXISTANTE       = 1;

  public function __construct($exceptionMessage = '', $code = null){
    switch($code){
      case self::ERREUR_CONNEXION:
        $message      = "Erreur de connexion";
        $description  = "$exceptionMessage";
        $type         = 'E';
        break;

      case self::TABLE_INEXISTANTE:
        $message      = "Table inexistante";
        $description  = "$exceptionMessage";
        $type         = 'E';
        break;
    }
    parent::__construct($message, $description, $type, $code);
  }
}
