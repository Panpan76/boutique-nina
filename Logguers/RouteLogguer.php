<?php

namespace Logguers;


/**
 * Classe RouteLogguer
 * Gère les logs relatives aux routes
 *
 * @author Panpan76
 * @date 08/11/2017
 */
class RouteLogguer{
  const FICHIER_LOG = 'Requete';

  const ROUTE_TROUVE        = 0;

  public static function log($logguerMessage = '', $code = null){
    $logguer = Logguer::getInstance(self::FICHIER_LOG);
    switch($code){
      case self::ROUTE_TROUVE:
        $message      = "Route trouvée";
        $description  = "$logguerMessage";
        $code         = 'I';
        break;
    }
    $message  = "[$message] : $description";
    $logguer->log($message, $code);
  }
}
