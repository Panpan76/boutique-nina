<?php

namespace Gestionnaires;

use \Config;
use Gestionnaires\Annotation as Annotation;

use Exceptions\BaseDeDonneesException as BDDException;

use \PDO;
use \PDOException;

/**
 * Classe BaseDeDonnees
 * Permet de gérer la connexion et les requêtes avec la base de données, en générant des exceptions en cas d'échec
 *
 * @author Panpan76
 * @date 09/11/2017
 */
class BaseDeDonnees{
  ###############
  ## Attributs ##
  ###############

  /**
   * @var BaseDeDonnees $instance L'instance singleton de la classe
   */
  private static $instance = null;

  /**
   * @var PDO $pdo L'instance de PDO
   */
  public $pdo;

  /**
   * @var string $base Base de données choisie
   */
  private $base;


  /**
   * @var array $mapping Annotations d'entités
   */
  private $mapping;


  /**
   * Permet d'avoir une seule instance de la classe
   *
   * @see __construct()
   *
   * @return Gestionnaires\BaseDeDonnees
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
  protected function __construct(){
    $base = Config::BaseDeDonnees();
    
    try{
      $this->pdo = new PDO("{$base['SGBD']}:host={$base['HOST']};dbname={$base['BASE']}", "{$base['USER']}", "{$base['PASS']}", array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
      $this->base = $base['BASE'];
      $this->mapping = Annotation::getAnnotations('Entites');
      $this->checkTables();
    }
    catch(PDOException $e){
      throw new BDDException($e->getMessage(), BDDException::ERREUR_CONNEXION);
    }
  }

  /**
   * Vérifie l'existence des tables utilisée par les entités
   */
  protected function checkTables(){
    $requete = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '{$this->base}'";
    $sql = $this->pdo->prepare($requete);
    if($sql->execute()){
      // On récupère toutes les tables de la base de données
      $resultats = $sql->fetchAll(PDO::FETCH_ASSOC);
      $tables = array();
      foreach($resultats as $table){
        $tables[] = $table['TABLE_NAME'];
      }

      foreach($this->mapping as $entite => $infos){ // Pour chaque entité
        try{
          if(isset($infos['table']) && !in_array($infos['table'], $tables)){ // Si la table n'existe pas en base mais est définie dans l'entité
            throw new BDDException("La table '{$infos['table']}' utilisée par la classe '{$entite}' n`existe pas en base.", BDDException::TABLE_INEXISTANTE);
          }
        }catch(BDDException $e){
          // NOTE Utilie lors de la phase de développement, à désactiver en prod
          $this->creerTable($infos); // On crée la table
        }

        if(isset($infos['attributs'])){
          foreach($infos['attributs'] as $attribut => $infosAttr){ // Pour chaque attribut
            try{
              if(isset($infosAttr['association']) && !in_array($infosAttr['association'], $tables)){ // Si une table d'association n'existe pas en base
                throw new BDDException("La table association '{$infosAttr['association']}' utilisée par la classe '{$entite}' n`existe pas en base.", BDDException::TABLE_INEXISTANTE);
              }
            }catch(BDDException $e){
              // NOTE Utilie lors de la phase de développement, à désactiver en prod
              $this->creerTableAssociation($entite, $infosAttr['type'], $infosAttr['association']); // On crée la table d'association
            }
          }
        }
      }
    }
  }

  /**
   * Crée une table suivant les informations passées (annotations)
   *
   * @param array $infos Informations relatives à la table
   * @return boolean
   */
  protected function creerTable($infos){
    $table = $infos['table'];
    unset($infos['table']);
    $requete = "CREATE TABLE {$table}";

    $requeteSub = array();
    $alterFK = array();
    foreach($infos['attributs'] as $attribut => $description){ // Pour chaque attribut
      if(isset($description['champ'])){
        $key = '';
        $special = '';
        $unique = '';
        $null = " NOT NULL";
        if(isset($description['key']) && $description['key'] == 'PK'){
          $key = " PRIMARY KEY";
        }
        if(isset($description['special']) && $description['special'] == 'AI'){
          $special = " AUTO_INCREMENT";
        }
        if(isset($description['nullable']) && $description['nullable'] == true){
          $null = " NULL";
        }
        if(isset($description['unique']) && $description['unique'] == true){
          $unique = " UNIQUE";
        }
        if(isset($description['type']) && preg_match('/^Entites\\\\(.*)$/', $description['type'], $match)){
          $description['type'] = 'int';
          $tableReference = $this->mapping["Entites\\{$match[1]}"]['table'];
          // On crée en parallèle une requête pour les contraintes de clés étrangères
          $alterFK[] = "ADD FOREIGN KEY ({$description['champ']}) REFERENCES {$tableReference}({$description['champ']}) ON DELETE CASCADE ON UPDATE CASCADE";
        }
        $requeteSub[] = "{$description['champ']} {$description['type']}{$null}{$key}{$special}{$unique}";
      }
    }

    $requete .= " (".implode(', ', $requeteSub).") ENGINE=InnoDB"; // On précise le moteur de stockage InnoDB (obligatoire pour les foreign key)
    $sql = $this->pdo->prepare($requete);
    if($sql->execute()){
      if(!empty($alterFK)){
        $requeteAlterFK = "ALTER TABLE {$table} ".implode(', ', $alterFK);
        $sql2 = $this->pdo->prepare($requeteAlterFK);
        if($sql2->execute()){
          return true;
        }
        return false;
      }
      return true;
    }
    return false;
  }

  /**
   * Crée une table d'association suivant les informations passées (annotations)
   *
   * @param Entite $entiteSource  Entité source
   * @param entite $entiteCible   Entité cible
   * @param string $table         Nom de la table à créer
   * @return boolean
   */
  protected function creerTableAssociation($entiteSource, $entiteCible, $table){
    $requete = "CREATE TABLE {$table}";

    $requeteSub = array();
    $alterFK = array();
    foreach(array($entiteSource, $entiteCible) as $entite){
      foreach($this->mapping[$entite]['attributs'] as $attribut => $description){ // Pour chaque attribut
        if(isset($description['key']) && $description['key'] == 'PK'){
          $key = " PRIMARY KEY";
          $requeteSub[] = "{$description['champ']} {$description['type']} NOT NULL";
          // On crée en parallèle une requête pour les contraintes de clés étrangères
          $alterFK[] = "ADD FOREIGN KEY ({$description['champ']}) REFERENCES {$this->mapping[$entite]['table']}({$description['champ']}) ON DELETE CASCADE ON UPDATE CASCADE";
          continue 2;
        }
      }
    }

    $requete .= " (".implode(', ', $requeteSub).") ENGINE=InnoDB"; // On précise le moteur de stockage InnoDB (obligatoire pour les foreign key)
    $sql = $this->pdo->prepare($requete);
    if($sql->execute()){
      if(!empty($alterFK)){
        $requeteAlterFK = "ALTER TABLE {$table} ".implode(', ', $alterFK);
        $sql2 = $this->pdo->prepare($requeteAlterFK);
        if($sql2->execute()){
          return true;
        }
        return false;
      }
      return true;
    }
    return false;
  }
}
