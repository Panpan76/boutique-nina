<?php
$menu = array(
  'Nos produits'      => 'liste_produits',
  'Qui sommes-nous ?' => 'informations'
);
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <?php
      foreach($menu as $label => $url){
        $active = '';
        $lien = genereLien($url);
        if(preg_match("#{$_SERVER['REQUEST_URI']}#", $lien)){
          $active = 'active';
        }
        echo "<li class='nav-item {$active}'><a class='nav-link' href='{$lien}'>{$label}</a></li>";
      }
      ?>
    </ul>
  </div>
</nav>
