<?php

namespace Formulaires\Types;

class TypeTextarea extends Type{
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
              <textarea name='{$this->name}'>{$valeur}</textarea>
            </div>
            <script type='text/javascript'>
              $(document).ready(function(){
                $('textarea[name=\"{$this->name}\"]').summernote({
                  lang: 'fr-FR',
                  height: 300,
                  minHeight: null,
                  maxHeight: null,
                  focus: true,
                  toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['superscript', 'subscript']],
                    ['fontsize', ['fontname', 'fontsize', 'color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['misc', ['codeview']]
                  ]
                });
              });
            </script>";
  }
}
