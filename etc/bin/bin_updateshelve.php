<?php
/*
* bin_main.php - model updateshelve
*
* maintained by:  Eugene Reut <eugene.reut@gmail.com>
* Please ALWAYS copy eugene.reut@gmail.com
* on emails.
*
*/
class Bin_Updateshelve extends Bin
{
	public $_dba;

	function __construct() {
		$this->_dba = $this->db_access();
		$this->_dba->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	function get_data($_idsh = null, $_listing = null) {
		if (isset($_POST['Updateshelve'])) {
			$message = $this->update_shelve($_POST['selectbookcase'], $_POST['shelve'], $_idsh);
		} elseif (isset($_POST['Deleteshelve'])) {
			$message = $this->delete_shelve(isset($_POST['agreetodelete']), $_idsh);
		} else {
			$message = null;
		}

		$content = $this->get_shelve($_idsh);
		$content = array_merge($content, $this->select_bookcases($_idsh));
		$message = array('houston' => $message);

		return array_merge($content, $message);
	}

	private function update_shelve($_idbookcase, $_shelve, $_idsh) {
		if (!empty($_shelve)) {
			try {
				$this->_dba->beginTransaction();

				$stmt = $this->_dba->prepare('UPDATE shelves SET nameshelve = :nsh, idbc = :idbc WHERE idsh = :idsh');
				$stmt->execute(array(':nsh' => $_shelve, ':idbc' => $_idbookcase, ':idsh' => $_idsh));

				# commit the transaction
				$this->_dba->commit();
				$message = 'Bookcase "' . $_shelve . '" updated';
			} catch (PDOException $e) {
				$this->_dba->rollBack();
				$message = 'Bookcase not updated, some error exception happened. ' . $e->getMessage();
			}
		} else {
			$message = null;
		}

		return $message;
	}

	private function delete_shelve($_agreetodelete, $_idsh) {
		if (isset($_agreetodelete) && $_agreetodelete == 'Yes') {
			try {
				$this->_dba->beginTransaction();

				//$stmt = $this->_dba->prepare('UPDATE bookcase SET namebookcase = :nbc, aboutbookcase = :abc WHERE idbc = :idbc');
				//$stmt->execute(array(':nbc' => $_bookcase, ':abc' => $_aboutbookcase, ':idbc' => $_idsh));

				# commit the transaction
				$this->_dba->commit();

				$message = 'Shelve deleted';
			} catch (PDOException $e) {
				$this->_dba->rollBack();
				$message = 'Bookcase not deleted, some error exception happened. ' . $e->getMessage();
			}
		} else {
			$message = 'Sorry, can be deleted only if you do agree to delete.';
		}

		return $message;
	}

	private function get_shelve($_idsh) {

		$stmt = $this->_dba->prepare('SELECT nameshelve, idbc FROM shelves WHERE idsh = :idsh');
		$stmt->execute([':idsh' => $_idsh]);
		$row_shelve = $stmt->fetch(PDO::FETCH_ASSOC);
		$_idbc = $row_shelve['idbc'];

		$stmt = $this->_dba->prepare('SELECT namebookcase FROM bookcase WHERE idbc = :idbc');
		$stmt->execute([':idbc' => $_idbc]);
		$row_bookcase = $stmt->fetch(PDO::FETCH_ASSOC);

		return array('NameShelve' => $row_shelve['nameshelve'], 'NameBookcase' => $row_bookcase['namebookcase']);
	}

	private function select_bookcases($_idsh) {
		$_Option = null;

		$stmt = $this->_dba->prepare('SELECT idbc FROM shelves WHERE idsh = :idsh');
		$stmt->execute([':idsh' => $_idsh]);
		$row_shelve = $stmt->fetch(PDO::FETCH_ASSOC);
		$_idbc_active = $row_shelve['idbc'];

		$statement = $this->_dba->query('SELECT idbc, namebookcase FROM bookcase');
		$data = $statement->fetchAll(); #fetch(PDO::FETCH_ASSOC)
		foreach($data as $row) {
      $_idbc = $row['idbc']; $_NameBC = $row['namebookcase'];

			if ($_idbc_active != $_idbc) {
				$_Option .= '<option value="'.$_idbc.'">' . $_NameBC . '</option>';
			} else {
				$_OptionActive = '<option value="'.$_idbc.'">' . $_NameBC . '</option>';
			}
		}

		$_OptionActive .= $_Option;

 	 	return array('select_bookcases' => $_OptionActive);
	}

	function get_title() {
		$_menu =$this->get_left_menu();
		$_title = array('title' => 'Тексты и Книги · Священник Яков Кротов');
		return array_merge($_title, $_menu);
	}

}
