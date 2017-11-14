<?php

namespace Exceptions;

/**
 * Classe FormulaireException
 * Gère les exceptions relatives aux formulaires. Etend la classe Exception
 *
 * @author Panpan76
 * @date 14/11/2017
 */
class FormulaireException extends Exception{
  const FICHIER_LOG = 'Formulaire';

  const AUCUN_TYPE        = 0;
  const AUCUNE_ANNOTATION = 1;
  const ATTRIBUT_INCONNU  = 2;
  const TOKEN_ABSENT      = 3;
  const TOKEN_INCORRECT   = 4;
  const TOKEN_MORT        = 5;

  public function __construct($description = '', $code = null){
    switch($code){
      case self::AUCUN_TYPE:
        $message      = "Aucun type";
        $description  = "$description";
        $type         = 'E';
        break;

      case self::AUCUNE_ANNOTATION:
        $message      = "Aucune annotation";
        $description  = "$description";
        $type         = 'E';
        break;

      case self::ATTRIBUT_INCONNU:
        $message      = "Attribut inconnu";
        $description  = "$description";
        $type         = 'E';
        break;

      case self::TOKEN_ABSENT:
        $message      = "Token absent";
        $description  = "$description";
        $type         = 'E';
        break;

      case self::TOKEN_INCORRECT:
        $message      = "Token incorrect";
        $description  = "$description";
        $type         = 'E';
        break;

      case self::TOKEN_MORT:
        $message      = "Token n`est pas valide";
        $description  = "$description";
        $type         = 'E';
        break;
    }
    parent::__construct($message, $description, $type, $code);
  }
}
