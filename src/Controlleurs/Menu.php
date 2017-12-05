<?php

namespace Controlleurs;

use Gestionnaires\Session;

class Menu extends Controlleur{

  /**
   * @Route('entete', url="/entete")
   */
  public function entete(){
    $session = Session::getInstance();
    $utilisateur = $session->getUtilisateurCourant();


    $this->rendersimple('menus/entete.php', array(
      'utilisateur' => $utilisateur
    ));
  }


  /**
   * @Route('principal', url="/principal")
   */
  public function principal(){

    $this->rendersimple('menus/principal.php');
  }


  /**
   * @Route('admin_menu', url="/admin_menu")
   */
  public function admin(){

    $this->rendersimple('menus/admin.php');
  }

}
