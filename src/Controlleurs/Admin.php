<?php

namespace Controlleurs;


class Admin extends Controlleur{

  /**
   * @Route('admin', url="/admin")
   * @requiert('Connecté')
   * @requiert(Role='Admin')
   */
  public function admin(){

    $this->render('admin/index.php', 'Panneau d\'administration', array(
    ));
  }

}
