<?php

namespace Entites;

/**
 * @BDD(table='droit')
 */
class Droit extends EntiteMere{
  /**
   * @BDD(champ='droit_ID')
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
}
