<?php
/*
* bin_main.php - model post
*
* maintained by:  Eugene Reut <eugene.reut@gmail.com>
* Please ALWAYS copy eugene.reut@gmail.com
* on emails.
*
*/
class Bin_Post extends Bin
{

	public $_dba;

	function __construct() {
		$this->_dba = $this->db_access();
	}

	function get_data($_idpt = NULL) {
		return $this->get_post($_idpt);
	}

	private function get_post($_idpt) {

		$stmt = $this->_dba->prepare('SELECT datepost, postname, filepath FROM postcase WHERE idpt = :idpt');
		$stmt->execute([':idpt' => $_idpt]);
		$row_postcase = $stmt->fetch(PDO::FETCH_ASSOC);

		$monthes = array(1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель', 5 => 'Май', 6 => 'Июнь', 7 => 'Июль',
							8 => 'Август', 9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь');

		$_year = date('Y', strtotime($row_postcase['datepost']));
		$_mnth = abs(date('m', strtotime($row_postcase['datepost'])));
		$_day = date('d', strtotime($row_postcase['datepost']));
		$postdate = $monthes[$_mnth] . ' ' . $_day . ', ' .  $_year;

		$postbody = file_get_contents($row_postcase['filepath']);

		$_bc_names = $this->get_addedname_bookcases($_idpt);
		$_sh_names = $this->get_addedname_shelves($_idpt);
		$_sort_eachother = $this->sortshelves_tobookcases($_bc_names, $_sh_names);

		$_netxpost = $this->get_nextpost($_idpt);

		return array('PostDate' => $postdate, 'PostName' => $row_postcase['postname'], 'PostBody' => $postbody, 'PostBcSh' => $_sort_eachother, 'NextPost' => $_netxpost);
	}

	private function get_nextpost($_idpt) {

		$_stmt_lastpost = $this->_dba->query('SELECT idpt FROM postcase ORDER BY idpt DESC');
		$_idpt_last = $_stmt_lastpost->fetch(PDO::FETCH_ASSOC);

		if ($_idpt < $_idpt_last['idpt']) {
			++$_idpt;
		} else {
			$_idpt = 1;
		}

		$stmt = $this->_dba->prepare('SELECT postname FROM postcase WHERE idpt = :idpt');
		$stmt->execute([':idpt' => $_idpt]);

		if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$_netxpost = $_idpt;
			$_postname = $row['postname'];
		} else {
			$_netxpost = $this->get_nextpost($_idpt);
		}

		return array($_postname, $_netxpost);
	}


	# functions get added names bookcases and shelves for under post menu
	private function get_addedname_bookcases($_idpt) {
		$_str = array(); $i = 0;

		$stmt = $this->_dba->prepare('SELECT idbc FROM postinbookcase WHERE idpt = :idpt');
		$stmt->execute([':idpt' => $_idpt]);

		while($row_stmt = $stmt->fetch(PDO::FETCH_ASSOC)) {

			$_bcstmt = $this->_dba->prepare('SELECT namebookcase FROM bookcase WHERE idbc = :idbc');
			$_bcstmt->execute([':idbc' => $row_stmt['idbc']]);
			$row_bookcase = $_bcstmt->fetch(PDO::FETCH_ASSOC);

			$_str[$i] = array('IdBC' =>  $row_stmt['idbc'], 'StrBC' => '<div id="addedbcid' . $row_stmt['idbc'] . '"><small class="smallbookcase"><a href="/bookcase?idbc='. $row_stmt['idbc'] .'">' . $row_bookcase['namebookcase'] . '</a></small></div>');
			++$i;
		}

		return array('NameBookcase' => $_str);
	}

	private function get_addedname_shelves($_idpt) {
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

	private function sortshelves_tobookcases($_bc_names, $_sh_names) {
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
				$_str .= '.';
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

	function get_title($_id) {
		$_menu =$this->get_left_menu($_id);
		$_title = array('title' => 'Тексты и Книги · Священник Яков Кротов');
		return array_merge($_title, $_menu);
	}

}
