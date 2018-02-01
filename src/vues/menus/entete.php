<?php
$nomEntete = \Config::Application()['NOM'];
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="navbar-collapse">
    <a class="nav-link clickable" href="<?= genereLien('accueil'); ?>"><h4><span class="fa fa-home" aria-hidden="true"></span> <?= $nomEntete ?></h4></a>
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
    </ul>
    <?php
    // Si connecté
    if(!is_null($utilisateur)){
    ?>
      <a class="nav-link clickable" href=""><?= $utilisateur->getNom(); ?></a>
      <?php
      // Si admin
      ?>
      <a class="navbar-brand" href="<?= genereLien('admin'); ?>" aria-expanded="false"><span class="fa fa-gear" aria-hidden="true"></span></a>
      <?php

      ?>
      <a class="navbar-brand" href="<?= genereLien('deconnexion'); ?>"><span class="fa fa-power-off" aria-hidden="true"></span></a>
    <?php
    }else{
    ?>
      <a class="nav-link clickable" href="login">Se connecter</a>
    <?php
    }
    ?>
  </div>
</nav>
