<?php

namespace Entites;

/**
 * @BDD(table='image')
 */
class Image extends EntiteMere{
  /**
   * @BDD(champ='image_ID')
   * @BDD(type='int')
   * @BDD(key='PK')
   * @BDD(special='AI')
   */
  protected $id;

  /**
   * @BDD(champ='fichier')
   * @BDD(type='file')
   * @fichier(destination='src/ressources/images')
   */
  protected $fichier;

  /**
   * @BDD(champ='nom')
   * @BDD(type='varchar(255)')
   */
  protected $nom;


}
