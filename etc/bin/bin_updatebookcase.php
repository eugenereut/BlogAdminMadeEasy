<?php
/*
* bin_main.php - model updatebookcase
*
* maintained by:  Eugene Reut <eugene.reut@gmail.com>
* Please ALWAYS copy eugene.reut@gmail.com
* on emails.
*
*/
class Bin_Updatebookcase extends Bin
{
	public $_dba;

	function __construct() {
		$this->_dba = $this->db_access();
		$this->_dba->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	function get_data($_idbc = NULL) {
		if (isset($_POST['Updatebookcase'])) {
			$message = $this->update_bookcase($_POST['bookcase'], $_POST['aboutbookcase'], $_idbc);
		} elseif (isset($_POST['Deletebookcase'])) {
			$message = $this->delete_bookcase(isset($_POST['agreetodelete']), $_idbc);
		} else {
			$message = null;
		}

		$content = $this->get_bookcase($_idbc);
		$message = array('houston' => $message);

		return array_merge($content, $message);
	}

	private function update_bookcase($_bookcase, $_aboutbookcase, $_idbc) {
		if (!empty($_bookcase)) {
			try {
				$this->_dba->beginTransaction();

				$stmt = $this->_dba->prepare('UPDATE bookcase SET namebookcase = :nbc, aboutbookcase = :abc WHERE idbc = :idbc');
				$stmt->execute(array(':nbc' => $_bookcase, ':abc' => $_aboutbookcase, ':idbc' => $_idbc));

				# commit the transaction
				$this->_dba->commit();
				if (!empty($_aboutbookcase)) {
					$_aboutbookcase = ' / ' . $_aboutbookcase;
				}
				$message = 'Bookcase "' . $_bookcase . ' ' . $_aboutbookcase . '" updated';
			} catch (PDOException $e) {
				$this->_dba->rollBack();
				$message = 'Bookcase not updated, some error exception happened. ' . $e->getMessage();
			}
		} else {
			$message = null;
		}

		return $message;
	}

	private function delete_bookcase($_agreetodelete, $_idbc) {
		if (isset($_agreetodelete) && $_agreetodelete == 'Yes') {
			try {
				$this->_dba->beginTransaction();

				//$stmt = $this->_dba->prepare('UPDATE bookcase SET namebookcase = :nbc, aboutbookcase = :abc WHERE idbc = :idbc');
				//$stmt->execute(array(':nbc' => $_bookcase, ':abc' => $_aboutbookcase, ':idbc' => $_idbc));

				# commit the transaction
				$this->_dba->commit();

				$message = 'Bookcase deleted';
			} catch (PDOException $e) {
				$this->_dba->rollBack();
				$message = 'Bookcase not deleted, some error exception happened. ' . $e->getMessage();
			}
		} else {
			$message = 'Sorry, can be deleted only if you do agree to delete.';
		}

		return $message;
	}

	private function get_bookcase($_idbc) {

		$stmt = $this->_dba->prepare('SELECT namebookcase, aboutbookcase FROM bookcase WHERE idbc = :idbc');
		$stmt->execute([':idbc' => $_idbc]);
		$row_bookcase = $stmt->fetch(PDO::FETCH_ASSOC);

		return array('NameBookcase' => $row_bookcase['namebookcase'], 'AboutBC' => $row_bookcase['aboutbookcase']);
	}

	function get_title() {
		$_menu =$this->get_left_menu();
		$_title = array('title' => 'Тексты и Книги · Священник Яков Кротов');
		return array_merge($_title, $_menu);
	}

}
