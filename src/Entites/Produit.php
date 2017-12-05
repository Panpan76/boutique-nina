<?php

namespace Entites;

/**
 * @BDD(table='produit')
 */
class Produit extends EntiteMere{
  /**
   * @BDD(champ='produit_ID')
   * @BDD(type='int')
   * @BDD(key='PK')
   * @BDD(special='AI')
   */
  protected $id;

  /**
   * @BDD(champ='nom')
   * @BDD(type='varchar(255)')
   */
  protected $nom;

  /**
   * @BDD(champ='description')
   * @BDD(type='varchar(255)')
   */
  protected $description;

  /**
   * @BDD(champ='image_ID')
   * @BDD(type='Entites\Image')
   */
  protected $image;

  /**
   * @BDD(champ='prix')
   * @BDD(type='float')
   */
  protected $prix;

}
