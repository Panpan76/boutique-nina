<?php

namespace Entites;


abstract class EntiteMere{

  public final function set($attribut, $valeur){
    if(preg_match('/^\$/', $attribut)){
      $attribut = substr($attribut, 1);
    }
    if(property_exists(get_called_class(), $attribut)){
      $this->$attribut = $valeur;
    }
  }

  public function __get($attribut){
    if(preg_match('/^\$/', $attribut)){
      $attribut = substr($attribut, 1);
    }
    if(!property_exists(get_called_class(), $attribut)){
      return null;
    }
    return $this->$attribut;
  }

  public function __call($methode, $arguments){
    /*
     * On retire les 3 premiers caractères
     * On passe en minuscule la première lettre de l'attibut
     */
    $attribut = lcfirst(substr($methode, 3));
    if(!strncasecmp($methode, 'get', 3)){
      return $this->$attribut;
    }
    if(!strncasecmp($methode, 'set', 3)){
      $this->$attribut = $arguments[0];
    }
    if(!strncasecmp($methode, 'add', 3)){
      if(is_null($this->$attribut)){
        $this->$attribut = array();
      }
      $this->{$attribut}[] = $arguments[0];
    }
  }
}
