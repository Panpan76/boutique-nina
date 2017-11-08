<?php

use Exceptions\ClasseException;

/**
 * Charge automatiquement une classe lors de son appel
 *
 * @param string $classe Nom de la classe (avec namespace)
 *
 * @author Panpan76
 * @date 08/11/2017
 */
function autoloadClassesPerso($classe){
  $nomClasse = $classe;
  // On remplace les \ du namespace par le caractère de séparation de dossier (windows : /, linux : \), pour faire correspondre l'arborescence
  $classe = str_replace('\\', DIRECTORY_SEPARATOR, $classe).'.php';
  if(file_exists($classe)){ // Si le fichier existe
    require_once $classe; // On l'inclu
    if(!class_exists($nomClasse) && !trait_exists($nomClasse) && !interface_exists($nomClasse)){
      // Si le fichier ne contenait pas le/la class/trait/interface
      throw new ClasseException($nomClasse, ClasseException::CLASSE_ABSENTE);
    }
    return true;
  }
  elseif(file_exists("src/$classe")){ // On regarde dans les fichiers du développeurs
    require_once "src/$classe"; // On l'inclu
    if(!class_exists($nomClasse) && !trait_exists($nomClasse) && !interface_exists($nomClasse)){
      // Si le fichier ne contenait pas le/la class/trait/interface
      throw new ClasseException($nomClasse, ClasseException::CLASSE_ABSENTE);
    }
    return true;
  }
   // Aucun fichier trouvé
  throw new ClasseException($classe, ClasseException::FICHIER_INTROUVABLE);
}

// On enregistre cette fonction comme autoload (elle se appelé automatiquement lors d'un appel à une classe, un trait ou une interface)
spl_autoload_register('autoloadClassesPerso');
