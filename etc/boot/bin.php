<?php
/*
* bin.php - base model
*
* maintained by:  Eugene Reut <eugene.reut@gmail.com>
* Please ALWAYS copy eugene.reut@gmail.com
*	on emails.
*
*/
class Bin
{
	public function get_data() {

	}

	function db_access() {
		try {
	  	$connection = new PDO('sqlite:opt/databases/ywdb.db', null, null, array(PDO::ATTR_PERSISTENT => true));
		}
		catch(PDOException $e) {
		    echo $e->getMessage();
		}

	  return $connection;
	}

}
