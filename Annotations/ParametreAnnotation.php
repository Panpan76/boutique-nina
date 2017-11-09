<?php

namespace Annotations;

use Interfaces\Analysable;


/**
 * Classe ParametreAnnotation
 * Permet de gérer le type d'annotation @param
 *
 * @author Panpan76
 * @date 09/11/2017
 */
class ParametreAnnotation implements Analysable{
  const REGEX = '/param\s([^\s]*)\s(\$[^\s]*)\s(.*)/';
  const COMMUN = 'parametres'; // On peut avoir plusieurs @param pour une même méthode, donc on met une clé commune

  /**
   * Analyse l'annotation
   *
   * @param string $annotation Annotation à analyser
   * @return array|false
   */
  public static function analyse($annotation){
    if(preg_match(self::REGEX, $annotation, $match)){
      return array(
        $match[2] => array(
          'type'        => $match[1],
          'description' => $match[3]
        )
      );
    }
    return false;
  }
}
