<?php

namespace Formulaires\Types;

class TypeTexte extends Type{
  private $nom;
  private $name;
  private $valeur;

  public function __construct($nom = '', $valeur = null){
    $nom = str_replace('_', ' ', $nom);
    $nom = ucfirst($nom);
    $this->nom    = $nom;
    $this->name   = $this::formatNom($this->nom);
    $this->valeur = $valeur;
  }

  public function getHTML(){
    $valeur = is_null($this->valeur) ? '' : $this->valeur;
    return "<div class='input-group'>
              <span class='input-group-addon'>{$this->nom}</span>
              <input type='text' class='form-control' placeholder='{$this->nom}' aria-label='{$this->nom}' name='{$this->name}' value='{$valeur}'/>
            </div>";
  }
}
