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
	public function get_data($_idbc = NULL) {

	}

	function db_access() {
		try {
	  	$connection = new PDO('sqlite:opt/databases/ywdb.db');
		}
		catch(PDOException $e) {
		    echo $e->getMessage();
		}

	  return $connection;
	}

	function get_left_menu() {
		$_i = 0; $_arr = array();

		$statement = $this->_dba->query('SELECT idbc, namebookcase FROM bookcase');
		$data = $statement->fetchAll();
		foreach($data as $row) {
      $_idbc = $row['idbc'];
      $_NameBC = $row['namebookcase'];
      $_menustr = '<a href="/bookcase?idbc='.$_idbc.'"><span>' . $_NameBC . '</span></a>';

			$_arr[$_i] = array('menustr' => $_menustr);
 			$_i++;
 		}

 	 	return array('leftmenu' => $_arr);
	}

}
