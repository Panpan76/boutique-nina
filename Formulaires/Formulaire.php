<?php

namespace Formulaires;

use Formulaires\Types\Type;
use Formulaires\Types\TypeNombre;
use Formulaires\Types\TypeTexte;
use Formulaires\Types\TypeTextarea;
use Formulaires\Types\TypeMotDePasse;
use Formulaires\Types\TypeToken;

use Gestionnaires\Annotation;
use Gestionnaires\Requete;
use Gestionnaires\GestionnaireRequetes;

use Exceptions\FormulaireException;



/**
 * Classe Formulaire
 * Permet de gérer automatiquement les formulaires, en fonction des entités, en générant des exceptions en cas d'échec
 *
 * @author Panpan76
 * @date 14/11/2017
 */
class Formulaire{
  private $entite;
  private $erreur;
  private $elements = array();
  private $boutons;

  public function __construct($entite){
    $this->entite = $entite;
    // On récupères les annotations
    $parser = Annotation::getInstance();
    $annots = $parser->getAnnotations('Entites');
    if(!isset($annots[$entite])){
      throw new FormulaireException("Aucune annotations pour l`entité '$entite'", FormulaireException::AUCUNE_ANNOTATION);
    }
    $annotations = $annots[$entite];


    $requete = Requete::getRequeteCourante();
    $parametres = $requete->getParametres();

    foreach($annotations['attributs'] as $attribut => $infos){
      if(!isset($infos['type'])){
        throw new FormulaireException("Aucun type définie pour l`attribut '$attribut' de l`entité '$entite'", FormulaireException::AUCUN_TYPE);
      }
      $param = null;
      if(isset($infos['special']) && $infos['special'] == 'AI'){
        continue;
      }
      if(isset($infos['champ'])){
        if(isset($parametres[Type::formatNom($infos['champ'])])){
          $param = $parametres[Type::formatNom($infos['champ'])];
        }
        preg_match('/^([^\(\\\]*)\(?.*\)?/', $infos['type'], $type);
        switch($type[1]){
          case 'int':
            $form = new TypeNombre($infos['champ'], $param);
            $this->elements[$attribut] = $form->getHTML();
            break;

          case 'varchar':
            $form = new TypeTexte($infos['champ'], $param);
            $this->elements[$attribut] = $form->getHTML();
            break;

          case 'text':
            $form = new TypeTextarea($infos['champ'], $param);
            $this->elements[$attribut] = $form->getHTML();
            break;
        }
      }
      if(isset($infos['crypt'])){
        $form = new TypeMotDePasse('Mot de passe', null);
        $this->elements[$attribut] = $form->getHTML();
      }
    }
  }

  public function setErreur($erreur = null){
    $this->erreur = $erreur;
  }

  public function getFormulaire(){
    $str = "<form action='' method='post'>";
    if(!is_null($this->erreur)){
      $str .= "<div class='alert alert-danger' role='alert'>
                {$this->erreur}
              </div>";
    }
    foreach($this->elements as $nom => $html){
      $str .= $html;
    }

    $token = new Token();
    $form = new TypeToken($token->getValeur());
    $requete = Requete::getRequeteCourante();
    $requete->ajoutParametre('token_source', $token);
    $str .= $form->getHTML();
    $str .= $this->boutons;
    $str .= '</form>';
    return $str;
  }

  public function retire($attribut){
    if(!preg_match('/^\$/', $attribut)){
      $attribut = "$$attribut";
    }
    if(!isset($this->elements[$attribut])){
      throw new FormulaireException("L`attribut '{$attribut}' est inconnu pour le formulaire de l`entité '{$this->entite}'", FormulaireException::ATTRIBUT_INCONNU);
    }
    unset($this->elements[$attribut]);
  }

  public function setBoutons($boutons = array()){
    $str = '';
    foreach($boutons as $bouton){
      switch($bouton){
        case 'Connexion':
          $str .= "<button type='submit' name='valide' class='btn btn-info clickable' value='true'>Se connecter</button>";
          break;

        case 'Oui':
          $str .= "<button type='submit' name='valide' class='btn btn-info clickable' value='true'>Oui</button>";
          break;

        case 'Non':
          $str .= "<button type='submit' name='valide' class='btn btn-danger clickable' value='false'>Non</button>";
          break;

        case 'Sauvegarder':
          $str .= "<button type='submit' name='valide' class='btn btn-info clickable' value='true'>Sauvegarder</button>";
          break;

        case 'Annuler':
          $requete = Requete::getRequeteCourante();
          $str .= "<a href='{$requete->getPagePrecedente()}'><span class='btn btn-warning clickable' value='false'>Annuler</span></a>";
          break;

        case 'Supprimer':
          $str .= "<button type='submit' name='valide' class='btn btn-danger clickable' value='true'>Supprimer</button>";
          break;
      }
    }
    $this->boutons = $str;
  }

  public function verifFormulaire(){
    $requete = Requete::getRequeteCourante();
    return $requete->estEnvoyee() && Token::verifToken();
  }

  public function getEntite(){
    if($this->verifFormulaire()){
      // On récupère la requête courante
      $requete = Requete::getRequeteCourante();
      // On récupère les paramètres de la requete
      $donnees = $requete->getParametres();
      if($donnees['valide'] == 'false'){
        return false;
      }
      // On récupères les annotations
      $parser = Annotation::getInstance();
      $annots = $parser->getAnnotations();
      if(!isset($annots[$this->entite])){
        throw new FormulaireException("Aucune annotations pour l`entité '$this->entite'", FormulaireException::AUCUNE_ANNOTATION);
      }
      $annotations = $annots[$this->entite];

      $entite = new $this->entite();

      foreach($annotations['attributs'] as $attribut => $infos){
        $setter = "Set".ucfirst(str_replace('$', '', $attribut));
        if(isset($infos['champ'])){
          $nom = str_replace('_', ' ', $infos['champ']);
          $nom = ucfirst($nom);
          $nom = Type::formatNom($nom);
          if(isset($donnees[$nom])){
            $entite->$setter($donnees[$nom]);
          }
        }
        if(isset($infos['crypt'])){
          $nom = str_replace('_', ' ', 'Mot de passe');
          $nom = ucfirst($nom);
          $nom = Type::formatNom($nom);
          if(isset($donnees[$nom])){
            $entite->$setter($donnees[$nom]);
          }
        }
      }
      return $entite;
    }
    return null;
  }
}
