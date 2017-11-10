<?php



function precedent(){
  $requete = Gestionnaires\Requete::getRequeteCourante();
  return $requete->getPagePrecedente();
}
