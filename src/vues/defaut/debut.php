<!doctype HTML>
<html>
  <head>
    <meta charset="utf-8" />
    <title><?= $titre; ?></title>
    <?php
    foreach($css['MIN'] as $fichier){
      $chemin = $css['DOSSIER'].$fichier;
      // $chemin = $fichier;
      echo "<link rel='stylesheet' href='$chemin' />\n";
    }
    foreach($js['MIN'] as $fichier){
      $chemin = $js['DOSSIER'].$fichier;
      // $chemin = $fichier;
      echo "<script type='text/javascript' src='$chemin'></script>\n";
    }
    ?>
  </head>
  <body class="container">
