<?php

namespace Exceptions;

class EntiteException extends Exception{
  const FICHIER_LOG = 'Entites';

  const AUCUN_ATTRIBUT      = 0;
  const AUCUN_CHAMP         = 1;
  const AUCUNE_TABLE        = 2;
  const AUCUN_RESULTAT      = 3;
  const INSERTION_IMPOSSIBLE    = 10;
  const SUPPRESSION_IMPOSSIBLE  = 11;
  const PARAMETRE_INCORRECT     = 12;
  const ERREUR_REQUETE          = 13;

  public function __construct($exceptionMessage = '', $code = null){
    switch($code){
      case self::AUCUN_ATTRIBUT:
        $message      = "Aucun attribut correspondant";
        $description  = "$exceptionMessage";
        $type         = 'E';
        break;

      case self::AUCUN_CHAMP:
        $message      = "Aucun champ correspondant";
        $description  = "$exceptionMessage";
        $type         = 'E';
        break;

      case self::AUCUNE_TABLE:
        $message      = "Aucune table définie";
        $description  = "$exceptionMessage";
        $type         = 'E';
        break;

      case self::AUCUN_RESULTAT:
        $message      = "Aucun résultat";
        $description  = "$exceptionMessage";
        $type         = 'W';
        break;

      case self::INSERTION_IMPOSSIBLE:
        $message      = "Insertion impossible";
        $description  = "$exceptionMessage";
        $type         = 'W';
        break;

      case self::SUPPRESSION_IMPOSSIBLE:
        $message      = "Suppression impossible";
        $description  = "$exceptionMessage";
        $type         = 'W';
        break;

      case self::PARAMETRE_INCORRECT:
        $message      = "Paramètre incorrect";
        $description  = "$exceptionMessage";
        $type         = 'E';
        break;

      case self::ERREUR_REQUETE:
        $message      = "Erreur lors de l'exécution de la reqûete";
        $description  = "$exceptionMessage";
        $type         = 'E';
        break;
    }
    parent::__construct($message, $description, $type, $code);
  }
}
