<?php

namespace Gestionnaires;

use Gestionnaires\Fichier;
use Annotations\Annotation as AnnotationParser;

use Exceptions\FichierException;


/**
 * Classe Annotation
 * Permet de gérer les annotations relatives à un fichier, en générant des exceptions en cas d'échec
 *
 * @author Panpan76
 * @date 09/11/2017
 */
class Annotation{
  ################
  ## Constantes ##
  ################
  const REGEX_ANNOTATION  = '/\*\s@(.*)[\n\r]*/'; // Une annotation commence par une étoile (*), suivi d'un espace (\s) puis un arobase (@) et se finit par un retour à la ligne
  const REGEX_ATTRIBUT    = '/(public|protected|private)\s(static)?\s?\$(\w*)[^\w]*(.*);/'; // Pour match un attribut
  const REGEX_METHODE     = '/(public|protected|private)\s(static)?\s?function\s(\w+).*{/'; // Pour match une méthode
  const REGEX_NAMESPACE   = '/^namespace\s(.+);/'; // Pour match le namespace (un par fichier)
  const REGEX_CLASSE      = '/class\s(\w+)\s?.*{/'; // Pour match la classe (une par fichier)

  ###############
  ## Attributs ##
  ###############
  private static $annotations = array(); // Toutes les annotations sauvegardées

  private static $instance;


  ##############
  ## Méthodes ##
  ##############

  /**
   * Permet d'avoir une seule instance de la classe
   *
   * @see __construct()
   *
   * @return Gestionnaires\Annotation
   */
  public static function getInstance(){
    if(is_null(self::$instance)){
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * Constructeur de la classe
   */
  public function __construct(){
    // TODO Mettre en mémoire les annotations -> fichier
    $this->init(); // Initialise les annotations
  }

  /**
   * Initiliase les annotations
   */
  private function init(){
    $this->parseDossier('src/Entites'); // Les entités
  }

  /**
   * Pour parser un dossier
   *
   * @param string $nomDossier Nom du dossier à parser
   */
  public function parseDossier($nomDossier){
    foreach(glob("$nomDossier/*.php") as $fichier){
      $this->parseFichier($fichier);
    }
    return self::$annotations;
  }


  public static function getAnnotations($element = null){
    if(is_null($element)){
      return self::$annotations;
    }

    $resultats = array();
    foreach(self::$annotations as $categorie => $infos){
      if(preg_match("/$element/", $categorie)){
        $resultats[$categorie] = self::$annotations[$categorie];
      }
    }
    return $resultats;
  }


  /**
   * Pour parser un fichier
   *
   * @param string $nomFichier Nom du fichier à parser
   */
  public function parseFichier($nomFichier){
    try{
      // On ouvre le fichier
      $fichier = new Fichier($nomFichier);

      $annotations = array();
      $namespace = '';

      // On lit le fichier ligne par ligne
      while(($str = $fichier->lireLigne()) !== false){
        if(preg_match(self::REGEX_NAMESPACE, $str, $match)){ // Si on match le namespace
          $namespace = $match[1];
        }
        if(preg_match(self::REGEX_CLASSE, $str, $match)){ // Si on match la classe
          $classe = $match[1];
          if(array_key_exists('table', $annotations)){
            self::$annotations["$namespace\\$classe"]['table'] = $annotations['table'];
            unset($annotations['table']);
          }
        }
        if(preg_match(self::REGEX_ANNOTATION, $str, $match)){ // Si on match une annotation
          $resultat = AnnotationParser::analyse($match[1]); // On cherche laquelle
          // $resultat contient tous le détail de l'annotation
          $cle = null;
          if(count($resultat)){ // On peut passer une clé depuis l'analyse pour regrouper certains types d'annotations
            $cle = array_keys($resultat)[0];
          }
          // On ajoute cette annotation à la liste des annotations
          if(array_key_exists($cle, $annotations)){
            $annotations[$cle] = array_merge($annotations[$cle], $resultat[$cle]);
          }
          else{
            $annotations = array_merge($annotations, $resultat);
          }
        }

        if(preg_match(self::REGEX_ATTRIBUT, $str, $match)){ // Si on match un attribut
          $resultat = array();
          $resultat['visibilite'] = $match[1]; // La visibilité
          if(!empty($match[2])){
            $resultat['static'] = true; // S'il est static
          }
          if(!empty($match[4])){
            $resultat['defaut'] = $match[4]; // S'il y a une valeur par défaut
          }
          // On le stock toutes les annotations précédentes dans la liste des attributs de la classe
          self::$annotations["$namespace\\$classe"]['attributs'][$match[3]] = array_merge($resultat, $annotations);
          // On vide le tableau des annotations
          $annotations = array();
        }
        if(preg_match(self::REGEX_METHODE, $str, $match)){ // Si on match une méthode
          $resultat = array();
          $resultat['visibilite'] = $match[1]; // La visibilité
          if(!empty($match[2])){
            $resultat['static'] = true; // s'il est static
          }
          // On le stock toutes les annotations précédentes dans la liste des méthodes de la classe
          self::$annotations["$namespace\\$classe"]['methodes'][$match[3]] = array_merge($resultat, $annotations);
          // On vide le tableau des annotations
          $annotations = array();
        }
      }
      $fichier->fermer();
      if(isset(self::$annotations["$namespace\\$classe"])){
        return self::$annotations["$namespace\\$classe"];
      }
      return array();
    }catch(FichierException $e){}
  }



  ###############
  ## Affichage ##
  ###############

  /**
   * Converti un tableau en chaine de caractère HTML
   *
   * @param array $tab Tableau à convertir
   * @return string
   */
  private function tableauVersChaine($tab){
    $str = '<ul>';
    foreach($tab as $type => $valeur){
      $str .= "<li>$type";
      if(is_array($valeur)){
        $str .= $this->tableauVersChaine($valeur);
      }
      else{
        $str .= " => $valeur";
      }
      $str .= '</li>';
    }
    $str .= '</ul>';
    return $str;
  }

  /**
   * Affiche le contenu des annotations
   *
   * @return string
   */
  public function __toString(){
    $str = '';
    $str .= $this->tableauVersChaine(self::$annotations);
    return $str;
  }
}
