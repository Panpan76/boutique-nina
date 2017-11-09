<?php

namespace Annotations;

use Interfaces\Analysable;

/**
 * Classe BDDAnnotation
 * Permet de gérer le type d'annotation @param
 *
 * @author Panpan76
 * @date 09/11/2017
 */
class BDDAnnotation implements Analysable{
  const REGEX_TABLE       = '/BDD\(table=\'(\w+)\'\)/';
  const REGEX_CHAMP       = '/BDD\(champ=\'(\w+)\'\)/';
  const REGEX_TYPE        = '/BDD\(type=\'(.+)\'\)/';
  const REGEX_KEY         = '/BDD\(key=\'(\w+)\'\)/';
  const REGEX_SPECIAL     = '/BDD\(special=\'(\w+)\'\)/';
  const REGEX_NULL        = '/BDD\(nullable\)/';
  const REGEX_ASSOCIATION = '/BDD\(association=\'(\w+)\'\)/';
  const REGEX_UNIQUE      = '/BDD\(unique\)/';
  const REGEX_CRYPT       = '/BDD\(crypt=\'(\w+)\'\)/';
  const REGEX_POSTSELECT  = '/BDD\(postSelect(,\s)?(\d*)?\)/';

  public static function analyse($annotation){
    if(preg_match(self::REGEX_TABLE, $annotation, $match)){
      return array(
        'table' => $match[1]
      );
    }
    if(preg_match(self::REGEX_CHAMP, $annotation, $match)){
      return array(
        'champ' => $match[1]
      );
    }
    if(preg_match(self::REGEX_TYPE, $annotation, $match)){
      return array(
        'type' => $match[1]
      );
    }
    if(preg_match(self::REGEX_KEY, $annotation, $match)){
      return array(
        'key' => $match[1]
      );
    }
    if(preg_match(self::REGEX_SPECIAL, $annotation, $match)){
      return array(
        'special' => $match[1]
      );
    }
    if(preg_match(self::REGEX_NULL, $annotation, $match)){
      return array(
        'nullable' => true
      );
    }
    if(preg_match(self::REGEX_ASSOCIATION, $annotation, $match)){
      return array(
        'association' => $match[1]
      );
    }
    if(preg_match(self::REGEX_UNIQUE, $annotation, $match)){
      return array(
        'unique' => true
      );
    }
    if(preg_match(self::REGEX_CRYPT, $annotation, $match)){
      return array(
        'crypt' => $match[1]
      );
    }
    if(preg_match(self::REGEX_POSTSELECT, $annotation, $match)){
      $nombre = 0;
      if(isset($match[2])){
        $nombre = $match[2];
      }
      return array(
        'postSelect' => intval($nombre)
      );
    }
    return false;
  }
}
