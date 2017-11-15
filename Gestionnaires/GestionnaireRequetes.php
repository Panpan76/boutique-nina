<?php

namespace Gestionnaires;

use Exceptions\RequeteException;
use Exceptions\SecuriteException;

use Logguers\RouteLogguer;

/**
 * Classe Requete
 * Permet de gérer les requêtes soumises, en générant des exceptions en cas d'échec
 *
 * @author Panpan76
 * @date 10/11/2017
 */
class GestionnaireRequetes{
  ###############
  ## Attributs ##
  ###############

  private static $instance;

  private $routes;

  private $requeteCourante;

  ##############
  ## Méthodes ##
  ##############

  /**
   * Permet d'avoir une seule instance de la classe
   *
   * @see __construct()
   *
   * @return Gestionnaires\Requete
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
    $annotationsParser = Annotation::getInstance();

    $annotations = $annotationsParser->getAnnotations('Controlleurs');
    $this->routes = $this->getRoutesFromAnnotations($annotations);

    $this->requeteCourante = new Requete();
  }


  /**
   * Récupère les routes avec leurs paramètres
   *
   * @param  array $annotations Annotations à trier
   * @return array              Les routes de l'application
   */
  private function getRoutesFromAnnotations($annotations){
    $routes = array();
    foreach($annotations as $controlleur => $element){
      foreach($element['methodes'] as $methode => $infos){
        if(!isset($infos['route'])){ // S'il n'y a pas de route, on passe
          continue;
        }
        foreach($infos['route'] as $nomRoute => $infosRoutes){
          $url = $infosRoutes['url'];
          $routes[$url]['nom'][]        = $nomRoute;
          $routes[$url]['controlleur']  = $controlleur;
          $routes[$url]['methode']      = $methode;

          if(isset($infos['requiert'])){
            $routes[$url]['requiert'] = $infos['requiert'];
          }

          if(isset($infos['parametres'])){
            $routes[$url]['parametres'] = $infos['parametres'];
          }
        }
      }
    }
    return $routes;
  }


  /**
   * Traite la requête passée en paramètre ou la requête courante si aucune n'est passée
   *
   * @param  Requete|null $requete Requete à traiter
   */
  public function traiteRequete($requete = null){
    if(is_null($requete)){
      $requete = $this->requeteCourante;
    }
    foreach($this->routes as $url => $infos){ // Pour chaque route
      $pattern = preg_replace('/{\$([^\/]*)}/U', '([^/]+)', $url); // Slash interdit
      $pattern = preg_replace('/{\$(.*)}/U', '(.+)', $pattern); // Slash autorisé si un slash est présent dans le paramètre

      if(preg_match("#^{$pattern}$#", $requete->getUrl(), $matches)){ // Si on a une correspondance
        RouteLogguer::log("La route {$requete->getUrl()} a été trouvé", RouteLogguer::ROUTE_TROUVE);

        $params = array(); // On prépare les variables de la route, les paramètres

        if(isset($infos['parametres'])){
          $n = 1;
          foreach($infos['parametres'] as $variable => $infosVariable){
            if(!preg_match('/^\$/', $variable)){ // Si ce n'est pas une variable, on passe
              continue;
            }
            if(preg_match('/^Entites/', $infosVariable['type'])){ // Si la variable est une entité
              $ge = GE::getEntite($infosVariable['type']); // On la récupère
              $params[] = $ge->get(intval($matches[$n])); // Selon sa clé primaire
            }
            else{
              $params[] = $matches[$n]; // Sinon, on récupère simplement ce qui est passé
            }
            $n++;
          }
        }

        try{
          if(isset($infos['requiert'])){
            // On vérifie que l'utilisateur a les droits pour cette requête
            $requete->verifDroits($infos['requiert']);
          }
        }catch(SecuriteException $e){
          switch($e->getCode()){
            case SecuriteException::ERREUR_CONNEXION:
              $infos['controlleur'] = 'Controlleurs\Controlleur';
              $infos['methode']     = 'dejaConnecte';
              break;

            case SecuriteException::ERREUR_NON_CONNEXION:
              $infos['controlleur'] = 'Controlleurs\Controlleur';
              $infos['methode']     = 'requiertConnecte';
              $params               = array($this->genereLien('login'));
              break;

            default:
              $infos['controlleur'] = 'Controlleurs\Controlleur';
              $infos['methode']     = 'requiertDroits';
              break;
          }
        }

        $controlleur  = new $infos['controlleur']();
        $methode      = $infos['methode'];

        try{
          call_user_func_array(array($controlleur, $methode), $params); // On appelle la méthode correspondant à la requête
        }catch(\Exception $e){
          call_user_func_array(array(new \Controlleurs\Controlleur(), 'exceptionNonCapturee'), array($e));
        }

        return;
      }
    }
    throw new RequeteException("L`url '{$requete->getUrl()}' ne correspond à aucune route connue", RequeteException::AUCUN_ROUTE_TROUVEE);
  }


  public function genereLien($nom){
    foreach($this->routes as $url => $infos){
      if(in_array($nom, $infos['nom'])){
        return \Config::Application()['ADRESSE'].$url;
      }
    }
    throw new Exceptions\RequeteException("La route '{$nom}' ne correspond à aucune route connue", RequeteException::AUCUN_ROUTE_TROUVEE);
  }


}
