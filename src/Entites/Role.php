<?php

namespace Entites;

/**
 * @BDD(table='role')
 */
class Role extends EntiteMere{
  /**
   * @BDD(champ='role_ID')
   * @BDD(type='int')
   * @BDD(key='PK')
   * @BDD(special='AI')
   */
  protected $id;

  /**
   * @BDD(champ='nom')
   * @BDD(type='varchar(255)')
   * @BDD(unique)
   */
  protected $nom;

  /**
   * @BDD(association='role_droits')
   * @BDD(type='Entites\Droit')
   */
  protected $droits;
}
