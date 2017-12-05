<?php
$menus = new Controlleurs\Menu();
$menus->entete();
$menus->admin();
?>
<h2>Ajouter un produit</h2>
<?= $formulaire; ?>
