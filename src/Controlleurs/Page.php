<?php

namespace Controlleurs;


class Page extends Controlleur{

  /**
   * @Route('accueil', url="/accueil")
   */
  public function accueil(){

    $this->render('pages/index.php', 'Accueil', array(
    ));
  }

  /**
   * @Route('informations', url="/informations")
   */
  public function informations(){

    $this->render('pages/index.php', 'Accueil', array(
    ));
  }

}
