<?php

namespace Exceptions;

/**
 * Classe RequeteException
 * Gère les exceptions relatives aux requêtes (adresse, url). Etend la classe Exception
 *
 * @author Panpan76
 * @date 10/11/2017
 */
class RequeteException extends Exception{
  const FICHIER_LOG = 'Requete';

  const AUCUN_ROUTE_TROUVEE     = 0;

  public function __construct($exceptionMessage = '', $code = null){
    switch($code){
      case self::AUCUN_ROUTE_TROUVEE:
        $message      = "Aucune route trouvée";
        $description  = "$exceptionMessage";
        $type         = 'E';
        break;
    }
    parent::__construct($message, $description, $type, $code);
  }
}
