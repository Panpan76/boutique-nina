<?php

namespace Controlleurs;

use Interfaces\ControlleurSecuriteInterface;

use \Config;

class Controlleur implements ControlleurSecuriteInterface{


  /**
   * Pour afficher une vue
   *
   * @param  string $vue       Fichier à inclure (depuis le dossier des vues)
   * @param  string $titre     Titre de la vue
   * @param  array  $variables Variables utilisées dans cette vue
   */
  protected function render($vue, $titre = '', $variables = array()){
    $vues    = Config::Vues(); // Récupère les infos des vues (dossier, etc.)
    $js      = Config::JS(); // Récupère les infos des fichiers javascript (dossier, fichiers minimum)
    $css     = Config::CSS(); // Récupère les infos des fichiers css (dossier, fichiers minimum)
    require_once $vues['DOSSIER'].$vues['debut']; // On inclu la vue de début
    foreach($variables as $prop => $valeur){ // On passe les variables à la vue en les nommant de la manière souhaitée
      $$prop = $valeur;
    }
    require_once $vues['DOSSIER'].$vue; // On inclu la vue demandée
    require_once $vues['DOSSIER'].$vues['fin']; // On inclu la vue de fin
  }

  /**
   * Même chose que render mais dans les JS/CSS et pages de début/fin (donc pas de titre)
   *
   * @param  string $vue       Fichier à inclure (depuis le dossier des vues)
   * @param  array  $variables Variables utilisées dans cette vue
   */
  protected function renderSimple($vue, $variables = array()){
    $vues = Config::Vues();
    foreach($variables as $prop => $valeur){
      $$prop = $valeur;
    }
    require_once $vues['DOSSIER'].$vue;
  }

  public function dejaConnecte(){
    // Redirection
    // TODO Vérifier existence de la route
    header('Location: '.genereLien('accueil'));
  }
  public function requiertConnecte($lien = null){
    http_response_code(401);
    return $this->render('erreurs/401.php', 'Identification requise', array(
      'lien' => $lien
    ));
  }
  public function requiertDroits(){
    http_response_code(403);
    return $this->render('erreurs/403.php', 'Identification requise');
  }
  public function pageIntrouvable(){
    http_response_code(404);
    return $this->render('erreurs/404.php', 'Page introuvable');
  }

  /**
   * Quand une exception n'est pas capturée
   *
   * @Route('exceptionNonCapturee', url="/exception")
   * @param \Exception $e Exception à afficher
   */
  public function exceptionNonCapturee($e){
    return $this->render('erreurs/exception.php', 'Exception non capturée', array(
      'exception' => $e
    ));
  }

  /**
   * Quand une exception n'est pas capturée et qu'elle est pasé par l'url
   *
   * @Route('exceptionNonCapturee_explicite', url="/exception/{$type}/{$code}/{$description/}")
   * @param string $type Type de l'exception
   * @param int $code Code de l'exception
   * @param string $description Description de l'exception
   */
  public function exceptionNonCaptureeExplicite($type, $code, $description){
    $type = "\Exceptions\\$type";
    $e = new $type($description, $code);
    return $this->render('erreurs/exception.php', 'Exception non capturée', array(
      'exception' => $e
    ));
  }
}
