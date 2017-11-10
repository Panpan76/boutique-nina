<?php

namespace Exceptions;

/**
 * Classe SecuriteException
 * Gère les exceptions relatives à la sécurité des requêtes (connexion, droits, etc.). Etend la classe Exception
 *
 * @author Panpan76
 * @date 10/11/2017
 */
class SecuriteException extends Exception{
  const FICHIER_LOG = 'Securite';

  const ERREUR_NON_CONNEXION    = 0;
  const ERREUR_CONNEXION        = 1;
  const ERREUR_ROLES            = 2;
  const ERREUR_DROITS           = 3;
  const IDENTIFIANTS_INVALIDES  = 4;

  public function __construct($exceptionMessage = '', $code = null){
    switch($code){
      case self::ERREUR_NON_CONNEXION:
        $message      = "Utilisateur non connecté";
        $description  = "$exceptionMessage";
        $type         = 'W';
        break;

      case self::ERREUR_CONNEXION:
        $message      = "Utilisateur connecté";
        $description  = "$exceptionMessage";
        $type         = 'W';
        break;

      case self::ERREUR_ROLES:
        $message      = "L`utilisateur n`a pas les rôles requis";
        $description  = "$exceptionMessage";
        $type         = 'W';
        break;

      case self::ERREUR_DROITS:
        $message      = "L`utilisateur n`a pas les droits requis";
        $description  = "$exceptionMessage";
        $type         = 'W';
        break;

      case self::IDENTIFIANTS_INVALIDES:
        $message      = "Identifiants invalides";
        $description  = "$exceptionMessage";
        $type         = 'W';
        break;

    }
    parent::__construct($message, $description, $type, $code);
  }
}
