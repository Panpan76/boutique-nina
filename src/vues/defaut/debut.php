<!doctype HTML>
<html>
  <head>
    <meta charset="utf-8" />
    <title><?= $titre; ?></title>
    <?php
    foreach($css['MIN'] as $fichier){
      $chemin = $css['DOSSIER'].$fichier;
      echo "<link rel='stylesheet' href='$chemin' />\n";
    }
    foreach($js['MIN'] as $fichier){
      $chemin = $js['DOSSIER'].$fichier;
      echo "<script type='text/javascript' src='$chemin'></script>\n";
    }
    ?>
  </head>
  <body class="container">
