<?php



function precedent(){
  $requete = Gestionnaires\Requete::getRequeteCourante();
  return $requete->getPagePrecedente();
}

/**
 * Alias de la méthode genereLien de la classe Gestionnaires\GestionnaireRequetes
 *
 * @param  string $nom Nom de la route à charger
 * @return string
 */
function genereLien($nom){
  return Gestionnaires\GestionnaireRequetes::getInstance()->genereLien($nom);
}
