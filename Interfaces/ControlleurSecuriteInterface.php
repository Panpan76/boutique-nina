<?php

namespace Interfaces;

/**
 * Interface ControlleurSecuriteInterface
 * Impose la présence de certaines méthodes
 *
 * @author Panpan76
 * @date 10/11/2017
 */
interface ControlleurSecuriteInterface{

  public function dejaConnecte();
  public function requiertConnecte($lien = null);
  public function requiertDroits();
  public function pageIntrouvable();
  public function exceptionNonCapturee($e);
  public function exceptionNonCaptureeExplicite($type, $code, $description);
}
