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
			  		$connection = new PDO('sqlite:opt/databases/blogadmin.db');
				}
				catch(PDOException $e) {
				    echo $e->getMessage();
				}

			  return $connection;
		}

		# get id bookcase in run_main
		function get_left_menu($_id = null) {
				$_i = 0; $_arr = array();

				# new defined in run_newpost.php
				if ($_id == 'new') {
						$_menustr = '<a href="/newblogs" class="active"><span>New</span></a>';
				} else {
						$_menustr = '<a href="/newblogs"><span>New</span></a>';
				}

				$_arr[0] = array('menustr' => $_menustr);

				$statement = $this->_dba->query('SELECT idbc, namebookcase FROM bookcase');
				$data = $statement->fetchAll();
				foreach($data as $row) {
						++$_i;

			      $_idbc = $row['idbc'];
			      $_NameBC = $row['namebookcase'];

						if ($_idbc == $_id) {
								$_menustr = '<a href="/bookcase?idbc='.$_idbc.'" class="active"><span>' . $_NameBC . '</span></a>';
						} else {
								$_menustr = '<a href="/bookcase?idbc='.$_idbc.'"><span>' . $_NameBC . '</span></a>';
						}

						$_arr[$_i] = array('menustr' => $_menustr);
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

		# functions get added names bookcases and shelves for under post menu
		function get_addedname_bookcases($_idpt) {
				$_str = array(); $i = 0;

				$stmt = $this->_dba->prepare('SELECT idbc FROM postinbookcase WHERE idpt = :idpt');
				$stmt->execute([':idpt' => $_idpt]);

				while($row_stmt = $stmt->fetch(PDO::FETCH_ASSOC)) {
						$_bcstmt = $this->_dba->prepare('SELECT namebookcase FROM bookcase WHERE idbc = :idbc');
						$_bcstmt->execute([':idbc' => $row_stmt['idbc']]);
						$row_bookcase = $_bcstmt->fetch(PDO::FETCH_ASSOC);

						$_str[$i] = array('IdBC' =>  $row_stmt['idbc'], 'StrBC' => '<div id="addedbcid' . $row_stmt['idbc'] . '"><small class="smallbookcase"><a href="/bookcase?idbc=' . $row_stmt['idbc'] .'">' . $row_bookcase['namebookcase'] . '</a></small></div>');

						++$i;
				}

				return array('NameBookcase' => $_str);
		}

		function get_addedname_shelves($_idpt) {
				$_str = array(); $i = 0;

				$stmt = $this->_dba->prepare('SELECT idsh FROM postonshelve WHERE idpt = :idpt');
				$stmt->execute([':idpt' => $_idpt]);

				while($row_stmt = $stmt->fetch(PDO::FETCH_ASSOC)) {
						$_shstmt = $this->_dba->prepare('SELECT nameshelve, idbc FROM shelves WHERE idsh = :idsh');
						$_shstmt->execute([':idsh' => $row_stmt['idsh']]);
						$row_shelve = $_shstmt->fetch(PDO::FETCH_ASSOC);

						$_shelve = '<div id="addedshlvid' . $row_stmt['idsh'] . '"><small class="smallshelve"><a href="/shelve?idsh='.$row_stmt['idsh'].'">' . $row_shelve['nameshelve'] . '</a></small></div>';

						$_str[$i] = array('IdBC' =>  $row_shelve['idbc'], 'StrSH' => $_shelve);

						++$i;
				}

				return array('NameShelves' => $_str);
		}

		function sortshelves_tobookcases($_bc_names, $_sh_names) {
				$_str = null;

				if (!empty($_bc_names['NameBookcase'])) {
						foreach ($_bc_names['NameBookcase'] as $value) {
								$_str .= $value['StrBC'];
								# cut shelves if idbc from postinbookcase == idbc in shelves
								$_strsh = $this->cut_shelves($value['IdBC'], $_sh_names);
								$_str .= $_strsh[0];
								$_sh_names = $_strsh[1];
				    }

						if (!empty($_sh_names['NameShelves'])) {
								//$_str .= '.';
								foreach ($_sh_names['NameShelves'] as $value) {
										$_str .= $value['StrSH'];
								}
					  }
			 	} else {
						if (!empty($_sh_names['NameShelves'])) {
								foreach ($_sh_names['NameShelves'] as $value) {
										$_str .= $value['StrSH'];
								}
						}
				}

				return $_str;
		}

		private function cut_shelves($_idbc, $_sh_names) {
				$_str = null; $i = 0;

				if (!empty($_sh_names)) {
						foreach ($_sh_names['NameShelves'] as $key => $value) {
								if($value['IdBC'] == $_idbc) {
										$_str .= $value['StrSH'];
										# if was any bookcase then shelve can repeats twice
										unset($_sh_names['NameShelves'][$key]);
								}
						}
			  }

				return array($_str, $_sh_names);
		}
}
