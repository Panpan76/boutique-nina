<?php

namespace Formulaires;

use Exceptions\FormulaireException;
use Gestionnaires\Requete;
use Gestionnaires\GestionnaireRequetes;
use Gestionnaires\Session;

class Token{
  private $valeur;
  private $etat;

  private static $CLE = "hTd!edZW5@htD31";

  const EN_ATTENTE  = 0;
  const VALIDE      = 1;
  const EXPIRE      = 2;

  public function __construct(){
    $this->valeur = $this->genereUniqueToken();
    $this->etat   = self::EN_ATTENTE;
  }

  private function genereUniqueToken(){
    $token = hash('sha256', time().self::$CLE);
    return $token;
  }


  public static function verifToken(){
    $requete = Requete::getRequeteCourante();
    if(($tokenCourant = $requete->getParametres()['token']) === null){
      throw new FormulaireException("Un token aurait dû être passé à cette requête", FormulaireException::TOKEN_ABSENT);
    }
    $requetesPrecedentes = Session::getInstance()->getSession()['requetes'];
    foreach($requetesPrecedentes as $requetePrecedente){
      if(isset($requetePrecedente->getParametres()['token_source'])){
        $tokenPrecedent = $requetePrecedente->getParametres()['token_source'];
        if(strcmp($tokenPrecedent->getValeur(), $tokenCourant) == 0){
          if($tokenPrecedent->getEtat() != self::EN_ATTENTE){
            throw new FormulaireException("Le token est dans l`état '{$tokenPrecedent->getEtatString()}'", FormulaireException::TOKEN_MORT);
          }
          $tokenPrecedent->setEtat(self::VALIDE);
          return true;
        }
      }
    }
    throw new FormulaireException("Le token fournit n`est pas bon", FormulaireException::TOKEN_INCORRECT);
    return false;
  }

  public function getValeur(){
    return $this->valeur;
  }

  public function getEtat(){
    return $this->etat;
  }

  public function getEtatString(){
    switch($this->etat){
      case self::EN_ATTENTE:
        return 'EN_ATTENTE';
        break;

      case self::VALIDE:
        return 'VALIDE';
        break;

      case self::EXPIRE:
        return 'EXPIRE';
        break;
    }
    return null;
  }

  public function setEtat($etat){
    $this->etat = $etat;
  }
}
