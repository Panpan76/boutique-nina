<?php

/**
 * Classe Config
 * Gère la configuration de l'application
 *
 * @author Panpan76
 * @date 10/11/2017
 */
class Config{

  public static function Application(){
    $app['NOM']     = "Projet";
    $app['ADRESSE'] = 'http://localhost/boutique-nina';

    return $app;
  }

  public static function BaseDeDonnees(){
    $base['SGBD'] = 'mysql';
    $base['HOST'] = 'localhost';
    $base['USER'] = 'root';
    $base['PASS'] = '';
    $base['BASE'] = 'boutique_nina';

    return $base;
  }


  public static function CSS(){
    $css['DOSSIER'] = self::Application()['ADRESSE'].'/src/ressources/css/';
    $css['MIN']     = array(
      'bootstrap'     => 'bootstrap.css',
      'font-awesome'  => 'font-awesome.css'
    );

    return $css;
  }

  public static function JS(){
    $js['DOSSIER'] = self::Application()['ADRESSE'].'/src/ressources/javascript/';
    $js['MIN']     = array(
      'jquery'    => 'jquery.js',
      'bootstrap' => 'bootstrap.js',
    );

    return $js;
  }

  public static function Vues(){
    $vues['DOSSIER'] = __DIR__.'/src/vues/'; // Emplacement du dossier des vues
    $vues['debut']   = 'defaut/debut.php'; // A remplacer par celle souhaitée
    $vues['fin']     = 'defaut/fin.php'; // A remplacer par celle souhaitée

    self::verifDossier($vues['DOSSIER']);
    return $vues;
  }

  public static function Emplacements(){
    $emplacements['ENTITES']      = __DIR__.'/src/Entites/'; // Dossier des entités
    $emplacements['CONTROLLEURS'] = __DIR__.'/src/Controlleurs/'; // Dossier des controlleurs

    self::verifDossier($emplacements['ENTITES']);
    self::verifDossier($emplacements['CONTROLLEURS']);
    return $emplacements;
  }

  /**
   * Vérifie/Crée le dossier spécifié
   *
   * @param string $dossier Dossier à vérifier/créer
   */
  private static function verifDossier($dossier){
    if(!is_dir($dossier)){
      if(!mkdir($dossier, 0777, true)){
        // TODO Lancer une exception
        echo "Impossible de créer le dossier $dossier";
      }
    }
  }
}
