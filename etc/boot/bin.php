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
	public function get_data($_idbc = null, $_listing = null) {

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

	# get id bookcase in run_main
	function get_left_menu($_id = null) {
		$_i = 0; $_arr = array();

		$statement = $this->_dba->query('SELECT idbc, namebookcase FROM bookcase');
		$data = $statement->fetchAll();
		foreach($data as $row) {
      $_idbc = $row['idbc'];
      $_NameBC = $row['namebookcase'];
			if ($_idbc == $_id) {
				$_menustr = '<a href="/bookcase?idbc='.$_idbc.'" class="active"><span>' . $_NameBC . '</span></a>';
			} else {
				$_menustr = '<a href="/bookcase?idbc='.$_idbc.'"><span>' . $_NameBC . '</span></a>';
			}

			$_arr[$_i] = array('menustr' => $_menustr);
 			$_i++;
 		}

 	 	return array('leftmenu' => $_arr);
	}

	# functions get the top shelves menu
	function get_topshelves_menu($_idbc, $_idsh) {
		$_str = array(); $i = 0; $_strbc = null;

		if ($_idsh) {
			$_shstmt = $this->_dba->prepare('SELECT idbc FROM shelves WHERE idsh = :idsh');
			$_shstmt->execute([':idsh' => $_idsh]);

			$_idbc = $_shstmt->fetch(PDO::FETCH_ASSOC);
			$_idbc = $_idbc['idbc'];
		} else {
			$_idbc = $_idbc;
		}

		$_shstmt = $this->_dba->prepare('SELECT idsh, nameshelve FROM shelves WHERE idbc = :idbc');
		$_shstmt->execute([':idbc' => $_idbc]);

		while($row_shelve = $_shstmt->fetch(PDO::FETCH_ASSOC)) {
			if ($row_shelve['idsh'] == $_idsh) {
				$_str[$i] = array('SrtSh' =>  '<li><a href="/shelve?idsh='.$row_shelve['idsh'].'" class="title parastyle"><span>' . $row_shelve['nameshelve'] . '</span></a></li>');
	    } else {
				$_str[$i] = array('SrtSh' =>  '<li><a href="/shelve?idsh='.$row_shelve['idsh'].'"><span>' . $row_shelve['nameshelve'] . '</span></a></li>');
	    }
			++$i;
		}

		$_stmt = $this->_dba->prepare('SELECT aboutbookcase FROM bookcase WHERE idbc = :idbc');
		$_stmt->execute([':idbc' => $_idbc]);

		if($row = $_stmt->fetch(PDO::FETCH_ASSOC)) {
			$_strbc = $row['aboutbookcase'];
		}

		return array('ShelvesMenu' => $_str, 'Aboutbookcase' => $_strbc);
	}

}
