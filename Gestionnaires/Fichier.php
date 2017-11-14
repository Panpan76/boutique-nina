<?php

namespace Gestionnaires;

use Exceptions\FichierException;

/**
 * Classe Fichier
 * Permet de gérer les actions relatives à un fichier, en générant des exceptions en cas d'échec
 *
 * @author Panpan76
 * @date 08/11/2017
 */
class Fichier{
  ###############
  ## Attributs ##
  ###############

  /**
   * @var string $nomFichier Nom du fichier
   */
  private $nomFichier;

  /**
   * @var ressource|false $fichier Ressource du fichier
   */
  private $fichier = false;

  /**
   * @param string $fichier Nom du fichier à ouvrir
   * @param string $mode    Mode d'ouverture du fichier (défaut: 'a+')
   *
   * @return void
   */
  public function __construct($fichier, $mode = 'a+'){
    if(preg_match('/(.*)[\/\\\]/', $fichier, $match)){
      $dossier = $match[1];
      $this->verificationDossier($dossier);
      if(!is_dir($dossier)){
        throw new FichierException($dossier, FichierException::CREATION_DOSSIER_IMPOSSIBLE);
      }
    }
    $this->nomFichier = $fichier;
    if(($this->fichier = fopen($fichier, $mode)) === false){
      throw new FichierException($this->nomFichier, FichierException::CREATION_IMPOSSIBLE);
    }
  }

  /**
   * @return string|false La ligne qui a été lu
   */
  public function lireLigne(){
    if(!$this->fichier){
      throw new FichierException($this->nomFichier, FichierException::AUCUN_FICHIER_OUVERT);
      return false;
    }
    if(($str = fgets($this->fichier)) !== false){
      return trim($str);
    }
    elseif(!feof($this->fichier)){
      throw new FichierException($this->nomFichier, FichierException::ERREUR_LECTURE);
      return false;
    }
    return false;
  }

  /**
   * @return array|false Les lignes du fichier
   */
  public function lireCommeTableau(){
    if(!$this->fichier){
      throw new FichierException($this->nomFichier, FichierException::AUCUN_FICHIER_OUVERT);
      return false;
    }
    $tab = array();
    while(($str = $this->lireLigne()) !== false){
      $tab[] = $str;
    }
    return $tab;
  }

  /**
   * @param string $str La chaine a écrire dans le fichier
   * @return boolean Vrai si l'écriture a réussi, faux sinon
   */
  public function ecrire($str){
    if(!$this->fichier){
      throw new FichierException($this->nomFichier, FichierException::AUCUN_FICHIER_OUVERT);
      return false;
    }
    $str .= "\r\n";
    if(fwrite($this->fichier, $str) === false){
      throw new FichierException($this->nomFichier, FichierException::ECRITURE_IMPOSSIBLE);
      return false;
    }
    return true;
  }

  public function vider(){
    if(!$this->fichier){
      throw new FichierException($this->nomFichier, FichierException::AUCUN_FICHIER_OUVERT);
      return false;
    }
    if(!fclose($this->fichier)){
      throw new FichierException($this->nomFichier, FichierException::FERMETURE_IMPOSSIBLE);
      return false;
    }
    $fichier = new self($this->nomFichier, 'w+');
    $fichier->fermer();
    unset($fichier);
    if(($this->fichier = fopen($this->nomFichier, 'a+')) === false){
      throw new FichierException($this->nomFichier, FichierException::CREATION_IMPOSSIBLE);
      return false;
    }
    return true;
  }

  /**
   * @return void
   */
  public function fermer(){
    if(!$this->fichier){
      throw new FichierException($this->nomFichier, FichierException::AUCUN_FICHIER_OUVERT);
      return false;
    }
    if(!fclose($this->fichier)){
      throw new FichierException($this->nomFichier, FichierException::FERMETURE_IMPOSSIBLE);
      return false;
    }
    return true;
  }

  /**
   * @return void
   */
  public function supprimer(){
    try{
      $this->fermer();
      if(!unlink($this->nomFichier)){
        throw new FichierException($this->nomFichier, FichierException::SUPPRESSION_IMPOSSIBLE);
        return false;
      }
      return true;
    }catch(FichierException $e){}
  }

  /**
   * Vérifie si un dossier existe, le crée le cas échéant
   *
   * @param string $dossier Dossier que l'on doit tester
   * @return void
   */
  private function verificationDossier($dossier){
    if(!is_dir($dossier)){
      if(!is_file($dossier)){
        if(!mkdir($dossier, 0777, true)){
          throw new FichierException($dossier, FichierException::CREATION_DOSSIER_IMPOSSIBLE);
        }
      }
    }
    return $dossier;
  }
}
