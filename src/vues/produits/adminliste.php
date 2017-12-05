<?php
$menus = new Controlleurs\Menu();
$menus->entete();
$menus->admin();
?>
<a class="btn btn-info" href="<?= genereLien('ajout_produit'); ?>">Ajouter un produit</a>

<div class="row">
<?php
foreach($produits as $produit){
?>
  <div class="col-sm-3">
    <div class="card">
      <img class="card-img-top" src="<?= $produit->getImage()->getFichier(); ?>" alt="Card image cap">
      <div class="card-body">
        <h4 class="card-title"><?= $produit->getNom(); ?></h4>
        <h6 class="card-subtitle mb-2 text-muted"><?= $produit->getPrix(); ?>€</h6>
        <p class="card-text"><?= $produit->getDescription(); ?></p>
        <a href="#" class="btn btn-primary">Go somewhere</a>
      </div>
    </div>
  </div>
<?php
}
?>
</div>
