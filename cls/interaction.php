<?php

/*
 * A single action or contribution which a user carries out on the system.
 */
abstract class interaction {

  /*
   * The ID assigned to this interaction in the database.
   */
  public $id;

  /*
   * The user who carried out the action.
   */
  public $user;

  /*
   * The time at which the action was carried out.
   */
  public $time;

}

?>
