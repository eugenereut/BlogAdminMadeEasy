<?php
/*
* bin_main.php - model newbookcase
*
* maintained by:  Eugene Reut <eugene.reut@gmail.com>
* Please ALWAYS copy eugene.reut@gmail.com
* on emails.
*
*/
class Bin_Newbookcase extends Bin
{
	public $_dba;

	function __construct() {
		$this->_dba = $this->db_access();
		$this->_dba->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	function get_data($_idbc = null, $_listing = null) {
		if (isset($_POST['Submitbookcase'])) {
			$message = $this->insert_newBookcase($_POST['bookcase'], $_POST['aboutbookcase']);
		} elseif (isset($_POST['Submitshelve'])) {
			$message = $this->insert_newShelves($_POST['selectbookcase'], $_POST['shelve']);
		} else {
			$message = null;
		}

		$content = $this->get_bookcases();
		$content = array_merge($content, $this->select_bookcases());
		$message = array('houston' => $message);

		return array_merge($content, $message);
	}

	private function insert_newBookcase($_bookcase, $_aboutbookcase) {
		if (!empty($_bookcase)) {
			try {
				$this->_dba->beginTransaction();

				$_stmt = $this->_dba->prepare('INSERT INTO bookcase (namebookcase, aboutbookcase) VALUES (?, ?)');
				$_stmt->execute(array($_bookcase, $_aboutbookcase));

				# commit the transaction
				$this->_dba->commit();
				if (!empty($_aboutbookcase)) {
					$_aboutbookcase = ' / ' . $_aboutbookcase;
				}
				$message = 'Bookcase "' . $_bookcase . ' ' . $_aboutbookcase . '" added';
			} catch (PDOException $e) {
				$this->_dba->rollBack();
				$message = 'Bookcase not added, some error exception happened. ' . $e->getMessage();
			}
		} else {
			$message = null;
		}

		return $message;
	}

	private function get_bookcases() {
		$arr_bookcase = array(); $i = 0;

		$statement = $this->_dba->query('SELECT idbc, namebookcase, aboutbookcase FROM bookcase');

		while($row_bookcase = $statement->fetch(PDO::FETCH_ASSOC)) {
			$_shelve = $this->get_shelves($row_bookcase['idbc']);

			$arr_bookcase[$i] = array('Record' => $row_bookcase['idbc'], 'NameBookcase' => $row_bookcase['namebookcase'], 'AboutBC' => $row_bookcase['aboutbookcase'], 'Shelve' => $_shelve);
			$i++;
		}

		return array('bookcase' => $arr_bookcase);
	}

	private function get_shelves($_idbc) {
		$str_shelves = null; $i = 0;

		$statement = $this->_dba->prepare('SELECT idsh, nameshelve FROM shelves WHERE idbc = :idbc');
		$statement->execute([':idbc' => $_idbc]);

		while($row_shelve = $statement->fetch(PDO::FETCH_ASSOC)) {
			$str_shelves  .= '<li><a href="/updateshelve?idsh=' . $row_shelve['idsh'].'">' . $row_shelve['nameshelve'] . '</a></li>';
		}

		return $str_shelves;
	}

	private function select_bookcases() {
		$_i = 0; $_arr = array();

		$statement = $this->_dba->query('SELECT idbc, namebookcase FROM bookcase');
		$data = $statement->fetchAll(); #fetch(PDO::FETCH_ASSOC)
		foreach($data as $row) {
      $_idbc = $row['idbc'];
      $_NameBC = $row['namebookcase'];
      $_Option = '<option value="'.$_idbc.'">' . $_NameBC . '</option>';

			$_arr[$_i] = array('option' => $_Option);
 			$_i++;
 		}

 	 	return array('select_bookcases' => $_arr);
	}

	private function insert_newShelves($_idbookcase, $_shelve) {
		if (!empty($_shelve) and !empty($_idbookcase)) {
			try {
				$this->_dba->beginTransaction();

				$_stmt = $this->_dba->prepare('INSERT INTO shelves (idbc, nameshelve) VALUES (?, ?)');
				$_stmt->execute(array($_idbookcase, $_shelve));

				# commit the transaction
				$this->_dba->commit();

				$message = 'Shelve "' . $_shelve . '" added';
			} catch (PDOException $e) {
				$this->_dba->rollBack();
				$message = 'Shelve not added, some error exception happened. ' . $e->getMessage();
			}
		} else {
			$message = 'Shelve not added.';
		}

		return $message;
	}

	function get_title() {
		$_menu =$this->get_left_menu();
		$_title = array('title' => 'Тексты и Книги · Священник Яков Кротов');
		return array_merge($_title, $_menu);
	}

}
