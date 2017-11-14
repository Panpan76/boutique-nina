<?php

namespace Formulaires\Types;

class TypeToken extends Type{
  private $valeur;

  public function __construct($valeur){
    $this->valeur = $valeur;
  }

  public function getHTML(){
    return "<input type='hidden' name='token' value='{$this->valeur}'/>";
  }

}
