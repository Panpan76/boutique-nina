<?php

namespace Gestionnaires;

use Gestionnaires\BaseDeDonnees as BDD;
use \PDO;

use Exceptions\EntiteException;

/**
 * Classe Entite
 * Permet de gérer les entités, en générant des exceptions en cas d'échec
 *
 * @author Panpan76
 * @date 09/11/2017
 */
class Entite{
  ###############
  ## Attributs ##
  ###############

  /**
   * @var array $instances Les instances d'entités (pour la sélection)
   */
  private static $instances = array();

  /**
   * @var array $entites Les instances des entités déjà instanciées
   */
  private static $entites = array();

  /**
   * @var string $entite Entité sélectionnée
   */
  private $entite;

  ##############
  ## Méthodes ##
  ##############

  /**
   * Permet d'avoir une seule instance de la classe par Entite
   *
   * @see __construct()
   *
   * @param string $entite  Entite souhaitée
   * @return Gestionnaires\Entite
   */
  public static function getEntite($entite){
    if(!in_array($entite, self::$instances)){
      self::$instances[$entite] = new self($entite);
    }
    return self::$instances[$entite];
  }

  /**
   * Constructeur de la classe
   *
   * @param string $entite Entite
   */
  private function __construct($entite){
    $this->entite = $entite;
  }



  /**
   * Permet de récupérer une instance de l'entité en fonction de son id
   *
   * @param int $id Identifiant unique de l'entité
   * @return Entite
   */
  public function get($id){
    if(!is_numeric($id)){
      throw new EntiteException("Impossible d`instancier l`entité '{$this->entite}' avec le paramètre '$id' ; entier attendu", EntiteException::PARAMETRE_INCORRECT);
    }
    // Si on a déjà récupéré l'entité, on la retourne. On évite de faire à nouveau la recherche
    if(($resultat = $this->verifExiste($id)) !== false){
      return $resultat;
    }

    $requete = "SELECT * FROM {$this->getTable()} WHERE {$this->getPK()} = $id";
    $bdd = BDD::getInstance();
    $sql = $bdd->pdo->prepare($requete);
    if($sql->execute()){
      // On fait appel à la méthode charger qui va créer l'objet et initialiser les valeurs et relations selon les résultats de la requête
      $obj = self::$entites[$this->entite][$id] = $this->charger($sql->fetch(PDO::FETCH_ASSOC));
      // On appelle les méthodes devant être appelées juste après la sélection
      $obj = $this->appelPostSelect($obj);
      // On stock l'objet retourné en mémoire et on le retourne
      return $obj;
    }
    throw new EntiteException($sql->errorInfo()[2], EntiteException::ERREUR_REQUETE);
  }


  /**
   * Récupère une instance de l'entité en fonction des paramètres de recherche renseignés
   *
   * @param array $where Paramètres de recherche
   * @param array $order Ordre de tri
   * @return Entite
   */
  public function getOneBy($where, $order = array()){
    if(empty($where) || !is_array($where)){
      throw new EntiteException('Tableau attendu', EntiteException::PARAMETRE_INCORRECT);
    }

    $requete = "SELECT {$this->getPK()} FROM {$this->getTable()} WHERE ";

    $listeWhere = array();
    foreach($where as $attribut => $valeur){
      $listeWhere[] = "{$this->getChampByAttribut($attribut)} = {$this->protegeChaine($valeur)}";
    }
    $requete .= implode(' AND ', $listeWhere);

    if(!empty($order)){
      $listeOrder = array();
      foreach($order as $attribut => $valeur){
        $listeOrder[] = "{$this->getChampByAttribut($attribut)} $valeur";
      }
      $requete .= " ORDER BY ".implode(', ', $listeOrder);
    }

    $bdd = BDD::getInstance();
    $sql = $bdd->pdo->prepare($requete);
    if($sql->execute()){
      $resultat = $sql->fetch(PDO::FETCH_ASSOC)[$this->getPK()];
      // Si on a un résultat
      if(!empty($resultat) && $resultat > 0){
        return $this->get($resultat);
      }
      throw new EntiteException("Aucun résultat pour la requête : $requete", EntiteException::AUCUN_RESULTAT);
    }
    throw new EntiteException($sql->errorInfo()[2], EntiteException::ERREUR_REQUETE);
  }



  /**
   * Récupère toutes les instances de l'entité en fonction des paramètres de recherche renseignés
   *
   * @param array $where Paramètres de recherche
   * @param array $order Ordre de tri
   * @return Entite
   */
  public function getAllBy($where = array(), $order = array()){
    $requete = "SELECT {$this->getPK()} FROM {$this->getTable()} ";

    if(!empty($where)){
      $listeWhere = array();
      foreach($where as $attribut => $valeur){
        $listeWhere[] = "{$this->getChampByAttribut($attribut)} = {$this->protegeChaine($valeur)}";
      }
      $requete .= "WHERE ".implode(' AND ', $listeWhere);
    }

    if(!empty($order)){
      $listeOrder = array();
      foreach($order as $attribut => $valeur){
        $listeOrder[] = "{$this->getChampByAttribut($attribut)} $valeur";
      }
      $requete .= "ORDER BY ".implode(', ', $listeWhere);
    }

    $bdd = BDD::getInstance();
    $sql = $bdd->pdo->prepare($requete);
    if($sql->execute()){
      $resultats = array();
      // On récupère toutes les correspondances
      while($resultat = $sql->fetch(PDO::FETCH_ASSOC)){
        $resultats[] = $this->get($resultat[$this->getPK()]);
      }
      if(!empty($resultats) && $resultats > 0){
        return $resultats;
      }
      throw new EntiteException("Aucun résultat pour la requête : $requete", EntiteException::AUCUN_RESULTAT);
    }
    throw new EntiteException($sql->errorInfo()[2], EntiteException::ERREUR_REQUETE);
  }


  /**
   * Charge les attributs de l'entité
   * Peux instancier d'autres entités si des relations sont présentes
   *
   * @param array $resultats Résultats de la requête, les champs présents en base
   * @return Entite
   */
  public function charger($resultats){
    // On instancie l'entité
    $obj = new $this->entite();

    if(!empty($resultats)){ // Si on a des résultats
      $annotations = Annotation::getInstance()->getAnnotations('Entites')[$this->entite];
      foreach($resultats as $champ => $valeur){
        foreach($annotations['attributs'] as $attribut => $infos){
          if(is_array($infos) && isset($infos['champ']) && $infos['champ'] == $champ){
            if($infos['type'] == 'int'){
              $valeur = intval($valeur);
            }
            $obj->set($attribut, $valeur); // On initialise la valeur

            if(preg_match('/^Entite/', $infos['type'])){ // Si on a un type entité
              $entite = self::getEntite($infos['type']);
              $valeur = $entite->get(intval($valeur)); // On récupère l'entité correspondante
              $obj->set($attribut, $valeur);
            }
            if(preg_match('/datetime/', $infos['type'])){ // Si c'est un type datetime
              $obj->set($attribut, new \DateTime($valeur)); // On récupère l'objet datetime avec la bonne valeur
            }
          }
          elseif(is_array($infos) && isset($infos['association'])){ // Si c'est un tableau d'entités (relation 1-n)
            // On va récupérer les relations, en utilisant les annotations de l'attributs ($infos) et clé primaire de notre entité (qui doit être la clé étrangère de celles que l'on va récupérer)
            $obj->set($attribut, $this->getAssociations($infos, array($this->getPK() => $obj->id)));
          }
        }
      }
    }
    return $obj; // On retourne l'objet avec ses valeurs
  }

  /**
   * Permet de sauvegarder un objet en base, aussi bien en insertion qu'en modification
   *
   * @param Entite $entite Entite à sauvegarder
   * @return Entite|null
   */
  public function sauvegarde($entite){
    $requeteInsert = "INSERT INTO {$this->getTable()} ";
    $requeteUpdate = "UPDATE {$this->getTable()} SET ";

    $requeteChamps = array();
    $requeteValeurs= array();
    $requeteChampsVal = array();

    $annotations = Annotation::getInstance()->getAnnotations('Entites')[$this->entite];
    foreach($annotations['attributs'] as $attribut => $infos){
      if(is_array($infos) && isset($infos['champ'])){
        $valeur = $entite->$attribut; // On récupère la valeur de l'attribut

        // Si c'est un champ en auto incrémente, on passe (il se remplira tout seul)
        if(isset($infos['special']) && $infos['special'] == 'AI'){
          continue;
        }
        // Si c'est un type Entité
        if(isset($infos['type']) && preg_match('/^Entites/', $infos['type'])){
          // Si c'est un tableau d'entités ou qu'aucune entité n'est présente, on ignore
          if(is_null($entite->$attribut) || is_array($entite->$attribut)){
            continue;
          }
          // On sauvegarde l'entité
          $ge = self::getEntite(get_class($entite->$attribut));
          $ge->sauvegarde($entite->$attribut);
          // On récupère seulement son identifiant pour la relation
          $valeur = $entite->$attribut->id;
        }

        // Si c'est un datetime
        if(isset($infos['type']) && preg_match('/^datetime/', $infos['type'])){
          // On met le bon format datetime de mysql
          $valeur = $valeur->format('Y-m-d H:i:s');
        }
        $requeteChamps[]    = $infos['champ']; // Pour l'insert
        $requeteValeurs[]   = $this->protegeChaine($valeur); // Pour l'insert
        $requeteChampsVal[] = end($requeteChamps)." = ".end($requeteValeurs); // Pour l'update
      }
    }
    $requeteInsert .= '('.implode(', ', $requeteChamps).') VALUES ('.implode(', ', $requeteValeurs).')'; // La requête pour l'insertion
    $requeteUpdate .= implode(', ', $requeteChampsVal)." WHERE {$this->getPK()} = {$entite->{$this->getAttributByChamp($this->getPK())}}"; // La requête pour la modification

    // Par défaut on insère
    $requete = $requeteInsert;
    if(!is_null($entite->{$this->getAttributByChamp($this->getPK())})){ // Si on avait déjà un ID de définie, c'est qu'elle était déjà sauvegardée, donc on modifie
      $requete = $requeteUpdate;
    }

    $bdd = BDD::getInstance();
    $sql = $bdd->pdo->prepare($requete);

    if($sql->execute()){
      if(!$entite->{$this->getAttributByChamp($this->getPK())}){ // Si on avait pas d'id, on récupère le dernier inséré
        $id = $bdd->pdo->lastInsertId();
        $entite->setId($id);
      }

      $annotations = Annotation::getInstance()->getAnnotations('Entites')[$this->entite];
      foreach($annotations['attributs'] as $attribut => $infos){
        if(is_array($infos) && isset($infos['type']) && preg_match('/^Entites/', $infos['type'])){
          if(is_array($entite->$attribut)){
            $this->setAssociationsByEntite($entite, $entite->$attribut, $infos);
          }
        }
      }
      return self::$entites[$this->entite][$entite->{$this->getAttributByChamp($this->getPK())}] = $entite;
    }
    throw new EntiteException($sql->errorInfo()[2]." ($requete)", EntiteException::INSERTION_IMPOSSIBLE);
  }

  /**
   * Permet de supprimer une entité
   *
   * @param Entite $entite Entité à supprimer
   * @return boolean
   */
  public function supprime($entite){
    $requete = "DELETE FROM {$this->getTable()} WHERE {$this->getPK()} = {$entite->{$this->getAttributByChamp($this->getPK())}}";

    $bdd = BDD::getInstance();
    $sql = $bdd->pdo->prepare($requete);

    if($sql->execute()){
      // On supprime de la mémoire l'entité enregistrée
      unset(self::$entites[$this->entite][$entite->{$this->getAttributByChamp($this->getPK())}]);
      unset($entite);
      return true;
    }
    throw new EntiteException($sql->errorInfo()[2]." ($requete)", EntiteException::SUPPRESSION_IMPOSSIBLE);
  }



  /**
   * Permet d'instancier les associations externes d'une entité
   *
   * @param array $infos Informations relatives à l'entité que l'on instancie
   * @param array $where Conditions pour trouver les dépendances
   * @return array
   */
  protected function getAssociations($infos, $where = array()){
    $table  = $infos['association'];
    $type   = $infos['type'];

    $requete = "SELECT * FROM $table";
    if(!empty($where)){
      $listeWhere = array();
      foreach($where as $champ => $valeur){
        $listeWhere[] = "{$champ} = $valeur";
      }
      $requete .= " WHERE ".implode(' AND ', $listeWhere);
    }

    $bdd = BDD::getInstance();
    $sql = $bdd->pdo->prepare($requete);
    if($sql->execute()){
      $resultats = array();
      $entiteAssoc = self::getEntite($type);
      while($resultat = $sql->fetch(PDO::FETCH_ASSOC)){
        $resultats[] = $entiteAssoc->get($resultat[$entiteAssoc->getPk()]); // On récupère l'entité
      }
      return $resultats;
    }
    throw new EntiteException($sql->errorInfo()[2], EntiteException::ERREUR_REQUETE);
  }


  /**
   * Sauvegarde les associations d'entités
   *
   * @param Entite $entite Entite possédant les associations
   * @param array $associations Associations à enregistrer
   * @param array $infos Informations relative à l'associations
   * @return boolean
   */
  protected function setAssociationsByEntite($entite, $associations, $infos){
    $table = $infos['association'];
    $ge = self::getEntite($infos['type']);

    $bdd = BDD::getInstance();

    // On supprime toutes les annotations de cette entité
    $requeteSupprime = "DELETE FROM $table WHERE {$this->getPK()} = {$entite->{$this->getAttributByChamp($this->getPK())}}";
    $sql = $bdd->pdo->prepare($requeteSupprime);

    if($sql->execute()){
      // On insère toutes les nouvelles associations
      $requete = "INSERT INTO $table ({$this->getPK()}, {$ge->getPK()}) VALUES ";

      $requeteValeurs = array();
      foreach($associations as $association){
        // On insère ou met à jour la relation avant
        $ge->sauvegarde($association); // On sauvegarde l'entité associée
        $requeteValeurs[] = "({$this->protegeChaine($entite->{$this->getAttributByChamp($this->getPK())})}, {$ge->protegeChaine($association->{$ge->getAttributByChamp($ge->getPK())})})";
      }
      if(empty($requeteValeurs)){
        return true;
      }
      $requete .= implode(', ', $requeteValeurs);

      $sql2 = $bdd->pdo->prepare($requete);

      if($sql2->execute()){
        return true;
      }
      throw new EntiteException($sql2->errorInfo()[2]." ($requete)", EntiteException::INSERTION_IMPOSSIBLE);
    }
    throw new EntiteException($sql->errorInfo()[2]." ($requeteSupprime)", EntiteException::SUPPRESSION_IMPOSSIBLE);
  }


  /**
   * Permet de d'appeler toutes les méthodes portant l'annotation 'postSelect'
   *
   * @param Entite $obj Objet sur lequel les appels doivent être fait
   * @return Entite
   */
  private function appelPostSelect($obj){
    $annotations = Annotation::getInstance()->getAnnotations('Entites')[$this->entite];
    if(!isset($annotations['methodes'])){
      return $obj;
    }
    $methodes = array();
    foreach($annotations['methodes'] as $methode => $infos){
      if(is_array($infos) && isset($infos['postSelect'])){
        $methodes[$infos['postSelect']] = $methode; // Pour ordonner les méthodes
        // NOTE Ne pas avoir 2 valeurs identiques !!! La dernière remplacera les autres sinon
      }
    }
    foreach($methodes as $methode){
      $obj->$methode();
    }
    return $obj;
  }


  /**
   * Permet de récupérer le nom du champ représentant l'id de l'entité
   *
   * @return string
   */
  public function getPK(){
    $annotations = Annotation::getInstance()->getAnnotations('Entites')[$this->entite];
    foreach($annotations['attributs'] as $attribut => $infos){
      if(is_array($infos) && isset($infos['key']) && $infos['key'] == 'PK'){
        return $infos['champ'];
      }
    }
    throw new EntiteException("Aucune clé primaire n'est définie pour l`entité '$this->entite'", EntiteException::AUCUNE_PK);
  }

  /**
   * Permet de récupérer l'attribut d'une entité en fonction du champ en base
   *
   * @return string
   */
  private function getAttributByChamp($champ){
    $annotations = Annotation::getInstance()->getAnnotations('Entites')[$this->entite];
    foreach($annotations['attributs'] as $attribut => $infos){
      if(is_array($infos) && isset($infos['champ']) && $infos['champ'] == $champ){
        return $attribut;
      }
    }
    throw new EntiteException("Aucun attribut ne correspond au champ '$champ' pour l`entité '$this->entite'", EntiteException::AUCUN_ATTRIBUT);
  }

  /**
   * Permet de récupérer le champ en base d'un attribut
   *
   * @return string
   */
  private function getChampByAttribut($attribut){
    $annotations = Annotation::getInstance()->getAnnotations('Entites')[$this->entite];
    if(isset($annotations['attributs'][$attribut]) && isset($annotations['attributs'][$attribut]['champ'])){
      return $annotations['attributs'][$attribut]['champ'];
    }
    throw new EntiteException("Aucun champ ne correspond à l`attribut '$attribut' pour l`entité '$this->entite'", EntiteException::AUCUN_CHAMP);
  }

  /**
   * Récupère la table de l'entité courante
   *
   * @return string
   */
  private function getTable(){
    $annotations = Annotation::getInstance()->getAnnotations('Entites')[$this->entite];
    if(isset($annotations['table'])){
      return $annotations['table'];
    }
    throw new EntiteException("Aucune table définie pour l`entité '$this->entite'", EntiteException::AUCUNE_TABLE);
  }

  /**
   * Protège une chaine en échapant les ' et les "
   *
   * @param string $chaine La chaine à protéger
   * @return string
   */
  private function protegeChaine($chaine){
    if(is_null($chaine)){
      return 'null';
    }
    $chaine = str_replace('\'', '\\\'', $chaine);
    $chaine = str_replace('"', '\"', $chaine);
    return "'$chaine'";
  }

  /**
   * Permet de retourner un objet déjà instancié plutôt que d'en charger à nouveau un autre
   *
   * @param int $id Identifiant de l'entité que l'on recherche
   *
   * @return Entite|false
   */
  private function verifExiste($id){
    if(isset(self::$entites[$this->entite]) && isset(self::$entites[$this->entite][$id])){
      return self::$entites[$this->entite][$id];
    }
    return false;
  }
}
