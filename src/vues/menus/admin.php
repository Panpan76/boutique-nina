<?php
$menu = array(
  'Produits'      => 'liste_produits_admin',
);
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <?php
      foreach($menu as $label => $url){
        $active = '';
        $lien = genereLien($url);
        $tmpLien = substr($lien, strlen(\Config::Application()['ADRESSE']));
        if(preg_match("#{$tmpLien}#", $_SERVER['REQUEST_URI'])){
          $active = 'active';
        }
        echo "<li class='nav-item {$active}'><a class='nav-link' href='{$lien}'>{$label}</a></li>";
      }
      ?>
    </ul>
  </div>
</nav>
