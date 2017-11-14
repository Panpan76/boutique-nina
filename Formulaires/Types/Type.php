<?php

namespace Formulaires\Types;

class Type{


  public static function formatNom($nom){
    $nom = strtolower($nom);
    $nom = preg_replace('/\s/', '', $nom);
    // Transforme les caractères accentués en entités HTML
    $nom = htmlentities($nom, ENT_NOQUOTES, 'utf-8');

    // Remplace les entités HTML pour avoir juste le premier caractères non accentués
    // Exemple : "&ecute;" => "e", "&Ecute;" => "E", "à" => "a" ...
    $nom = preg_replace('#&([a-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $nom);

    // Remplace les ligatures tel que : , Æ ...
    // Exemple "œ" => "oe"
    $nom = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $nom);
    // Supprime le reste
    $nom = preg_replace('#&[^;]+;#', '', $nom);

    return $nom;
  }

}
