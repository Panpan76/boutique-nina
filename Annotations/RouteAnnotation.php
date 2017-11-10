<?php

namespace Annotations;

use Interfaces\Analysable;

/**
 * Classe RouteAnnotation
 * Permet de gérer le type d'annotation @Route
 *
 * @author Panpan76
 * @date 10/11/2017
 */
class RouteAnnotation implements Analysable{
  const REGEX = '/Route\(\'(.*)\', url="(.*)"\)/'; // Route('<nom de la route>', url="<url correspondante>")
  const COMMUN = 'route';

  /**
   * Analyse l'annotation
   *
   * @param string $annotation Annotation à analyser
   * @return array|false
   */
  public static function analyse($annotation){
    if(preg_match(self::REGEX, $annotation, $match)){
      return array(
        $match[1] => array(
          'url' => $match[2]
        )
      );
    }
    return false;
  }
}
