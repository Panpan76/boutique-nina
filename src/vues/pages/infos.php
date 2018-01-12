<?php
$menus = new Controlleurs\Menu();
$menus->entete();
$menus->principal();
?>
<div class="row">
  <div class="col-sm-7">
    <div class="card">
      <img class="card-img-top" src="http://www.bio-uzes.fr/Images/BoutBio3.jpg" alt="Card image cap">
      <div class="card-body">
        <h4 class="card-title">Notre boutique</h4>
        <p class="card-text">

  Post quorum necem nihilo lenius ferociens Gallus ut leo cadaveribus pastus multa huius modi scrutabatur. quae singula narrare non refert, me professione modum, quod evitandum est, excedamus.<br /><br />

  Saepissime igitur mihi de amicitia cogitanti maxime illud considerandum videri solet, utrum propter imbecillitatem atque inopiam desiderata sit amicitia, ut dandis recipiendisque meritis quod quisque minus per se ipse posset, id acciperet ab alio vicissimque redderet, an esset hoc quidem proprium amicitiae, sed antiquior et pulchrior et magis a natura ipsa profecta alia causa. Amor enim, ex quo amicitia nominata est, princeps est ad benevolentiam coniungendam. Nam utilitates quidem etiam ab iis percipiuntur saepe qui simulatione amicitiae coluntur et observantur temporis causa, in amicitia autem nihil fictum est, nihil simulatum et, quidquid est, id est verum et voluntarium.<br /><br />

  Unde Rufinus ea tempestate praefectus praetorio ad discrimen trusus est ultimum. ire enim ipse compellebatur ad militem, quem exagitabat inopia simul et feritas, et alioqui coalito more in ordinarias dignitates asperum semper et saevum, ut satisfaceret atque monstraret, quam ob causam annonae convectio sit impedita.

  </p>
      </div>
    </div>
  </div>
  <div class="col-sm-5">
    <div id="map" style="height: 500px;"></div>
  </div>
</div>

<script>
  function initMap() {
    var uluru = {lat: 49.494302, lng: 1.143258};
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 17,
      center: uluru
    });
    var marker = new google.maps.Marker({
      position: uluru,
      map: map
    });
  }
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBzHEMw1pkmu5e1NhW7x4uAdS17fWF4QSc&callback=initMap"
  type="text/javascript"></script>
