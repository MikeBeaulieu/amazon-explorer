<?php
class pdoClass {
  // private statics to hold the connection
  private static $dbConnection = null;
 
  // make the next 2 functions private to prevent normal
  // class instantiation
  private function __construct() {
  }
  private function __clone() {
  }
 
  public static function getConnection() {
    // if there isn't a connection already then create one
    if ( !self::$dbConnection ) {
      try {
	self::$dbConnection = new PDO('sqlite: database/amazon.db');
	self::$dbConnection−>setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
      }
      catch( PDOException $e ) {
 
	echo $e−>getMessage();
      }
    }
    // return the connection
    return self::$dbConnection;
  }
}