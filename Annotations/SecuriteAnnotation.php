<?php

namespace Annotations;

use Interfaces\Analysable;

/**
 * Classe SecuriteAnnotation
 * Permet de gérer les types d'annotations relative à la sécurité
 *
 * @author Panpan76
 * @date 10/11/2017
 */
class SecuriteAnnotation implements Analysable{
  const REGEX_CONNECTE      = '/requiert\(\'?Connecté\'?\)/';
  const REGEX_NON_CONNECTE  = '/requiert\(\'?Non connecté\'?\)/';
  const REGEX_ROLES         = '/requiert\(Role="(.*)"\)/';
  const REGEX_DROITS        = '/requiert\(Droit="(.*)"\)/';
  const COMMUN = 'requiert';

  /**
   * Analyse l'annotation
   *
   * @param string $annotation Annotation à analyser
   * @return array|false
   */
  public static function analyse($annotation){
    if(preg_match(self::REGEX_CONNECTE, $annotation, $match)){
      return array(
        'connecte' => true
      );
    }
    if(preg_match(self::REGEX_NON_CONNECTE, $annotation, $match)){
      return array(
        'connecte' => false
      );
    }
    if(preg_match(self::REGEX_ROLES, $annotation, $match)){
      return array(
        'roles' => preg_split('/[\|,]/', $match[1])
      );
    }
    if(preg_match(self::REGEX_DROITS, $annotation, $match)){
      return array(
        'droits' => preg_split('/[\|,]/', $match[1])
      );
    }
    return false;
  }
}
