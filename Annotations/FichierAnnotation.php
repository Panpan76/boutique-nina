<?php

namespace Annotations;

use Interfaces\Analysable;

/**
 * Classe FichierAnnotation
 * Permet de gérer le type d'annotation @fichier
 *
 * @author Panpan76
 * @date 09/11/2017
 */
class FichierAnnotation implements Analysable{
  const REGEX_FICHIER = '/fichier\(destination=\'(.+)\'\)/';

  public static function analyse($annotation){
    if(preg_match(self::REGEX_FICHIER, $annotation, $match)){
      return array(
        'destination' => $match[1]
      );
    }
    return false;
  }
}
