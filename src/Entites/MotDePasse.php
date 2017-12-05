<?php

namespace Entites;

/**
 * @BDD(table='password')
 */
class MotDePasse extends EntiteMere{
  /**
   * @BDD(champ='password_id')
   * @BDD(type='int')
   * @BDD(key='PK')
   * @BDD(special='AI')
   */
  protected $id;

  /**
   * @BDD(champ='user_ID')
   * @BDD(type='Entites\Utilisateur')
   */
  protected $utilisateur;

  /**
   * @BDD(champ='password')
   * @BDD(type='varchar(255)')
   */
  protected $motDePasse;

  /**
   * @BDD(champ='date_utilisation')
   * @BDD(type='datetime')
   */
  protected $dateUtilisation;


}
