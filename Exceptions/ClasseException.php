<?php

namespace Exceptions;

/**
 * Classe ClasseException
 * Gère les exceptions relatives aux classes. Etend la classe Exception
 *
 * @author Panpan76
 * @date 08/11/2017
 */
class ClasseException extends Exception{
  const FICHIER_LOG = 'Classes';

  const FICHIER_INTROUVABLE = 0;
  const CLASSE_ABSENTE      = 1;
  const METHODE_ABSENTE     = 2;

  public function __construct($nomClasse = '', $code = null){
    switch($code){
      case self::FICHIER_INTROUVABLE:
        $titre        = "Fichier introuvable";
        $description  = "Le fichier indiqué comme contenant la classe '$nomClasse' n'a pas été trouvé";
        $code         = 'E';
        break;

      case self::CLASSE_ABSENTE:
        $titre        = "Classe absente";
        $description  = "Le fichier devant contenir la classe '$nomClasse' a été inclus, mais celle-ci n'y était pas";
        $code         = 'E';
        break;

      case self::METHODE_ABSENTE:
        $titre        = "Méthode absente";
        $description  = "$nomClasse";
        $code         = 'E';
        break;
    }
    parent::__construct($titre, $description, $code);
  }
}
