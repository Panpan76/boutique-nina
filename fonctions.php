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
  try{
    return Gestionnaires\GestionnaireRequetes::getInstance()->genereLien($nom);
  }catch(Exceptions\RequeteException $e){
    switch($e->getCode()){
      case Exceptions\RequeteException::AUCUN_ROUTE_TROUVEE:
        $classe = basename(get_class($e));
        header('Location: '.\Config::Application()['ADRESSE']."/exception/{$classe}/{$e->getCode()}/{$e->getDescription()}");
      // echo genereLien('exceptionNonCapturee_explicite')."/{$e->getType()}/{$e->getCode()}/{$e->getDescription()}";
        // header('Location: '.genereLien('exceptionNonCaptureeExplicite')."/{$e->getType()}/{$e->getCode()}/{$e->getDescription()}");
        // call_user_func_array(array(new \Controlleurs\Controlleur(), 'exceptionNonCapturee'), array($e));
        break;
    }
  }
}
