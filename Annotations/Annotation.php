<?php

namespace Annotations;


use Interfaces\Analysable;

use Exceptions\ClasseException;

/**
 * Classe Annotation
 * Permet de gérer les types d'annotations, en générant des exceptions en cas d'échec
 *
 * @author Panpan76
 * @date 09/11/2017
 */
class Annotation implements Analysable{
  ##############
  ## Méthodes ##
  ##############

  /**
   * Récupère les informations d'une annotation
   *
   * @param string $annotation Annotation devant être traitée
   * @return array
   */
  public static function analyse($annotation){
    // Pour chaque fichier de ce dossier, qui finit par "Annotation.php"
    foreach(glob(__DIR__.'/?*Annotation.php') as $classe){
      try{
        $classe = 'Annotations\\'.basename(explode('.', $classe)[0]); // On recrée le nom de la classe (avec le namespace) depuis le nom du fichier
        if(!in_array('analyse', get_class_methods($classe))){ // Si la classe n'a pas la méthode analyse
          throw new ClasseException("Le classe '$classe' devait contenir la méthode 'analyse', mais celle-ci n'y était pas (vérifier que '$classe' implémente l'interface 'Interfaces\Analyse')", ClasseException::METHODE_ABSENTE);
        }
        if(($resultat = $classe::analyse($annotation)) !== false){ // Si on a une correspondance
          if(defined("$classe::COMMUN")){ // Si on a définit une entrée commune (pour regrouper différents types d'annotations)
            $resultat = array(
              $classe::COMMUN => $resultat
            );
          }
          return $resultat;
        }
      }catch(ClasseException $e){}
    }
    return array();
  }
}
