<?php

namespace Interfaces;


/**
 * Interface UtilisateurInterface
 * Impose la présence des méthodes nécessaires à la sécurité
 *
 * @author Panpan76
 * @date 14/11/2017
 */
interface UtilisateurInterface{

  public function getRoles();
  public function getDroits();
}
