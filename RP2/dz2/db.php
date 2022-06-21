<?php

require_once 'dbSettings.php';

class DB {

    private static $db = null;

    private function __construct() {}
    private function __clone() {}

    public static function getConnection() {

        if(DB::$db === null) {
            global $db_base, $db_user, $db_pass;

            try {
                DB::$db = new PDO($db_base, $db_user, $db_pass);
                
                DB::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    	DB::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            } catch (PDOException $e) {
                exit('Connection failed: ' . $e->getMessage() );
            }
        }

        return DB::$db;
    }

}
 
?>