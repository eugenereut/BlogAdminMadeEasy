<?php
/*
* bin_main.php - model Setuppost
*
* maintained by:  Eugene Reut <eugene.reut@gmail.com>
* Please ALWAYS copy eugene.reut@gmail.com
* on emails.
*
*/
class Bin_Setuppost extends Bin
{
	public $_dba;

	function __construct() {
		$this->_dba = $this->db_access();
		$this->_dba->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	//setcookie("idpost", "", time() - 3600); // delete cookies
	function get_data($_idpost = null) {

		if (isset($_POST['addposttobcs'])) {
			$_idbc = $_POST['addposttobcs'];

			$echorespond = $this->addpost_tobcs($_idbc,  $_idpost);
			die($echorespond);
		}	elseif (isset($_POST['deletepostfrombcs'])) {
			$_idbc = $_POST['deletepostfrombcs'];

			$echorespond = $this->deletepost_frombcs($_idbc,  $_idpost);
			die($echorespond);
		}

		if (isset($_POST['addposttoshelve'])) {
			$_idshlv = $_POST['addposttoshelve'];

			$echorespond = $this->addpost_toshelve($_idshlv,  $_idpost);
			die($echorespond);
		}	elseif (isset($_POST['deletepostfromshelve'])) {
			$_idshlv = $_POST['deletepostfromshelve'];

			$echorespond = $this->deletepost_fromshelve($_idshlv,  $_idpost);
			die($echorespond);
		}

		# this cookie needs when post goes to bookcase or on shelves, see Run_Setuppost controller
		$cookie_name = 'idpost';
		$cookie_value = $_idpost;
		setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day

		if(!isset($_COOKIE['idpost'])) {
    	$message = array('houston' => 'Cookie is not set! Please allow cookies in browser settings.');
			$content = array_merge($message, $this->get_bookcases($_idpost));
		} else {
			$content = $this->get_post($_idpost);
			$content = array_merge($content, $this->get_bookcases($_idpost));
		}

		return $content; //array_merge($content, $message);
	}

	private function get_post($_idpt) {

		$stmt = $this->_dba->prepare('SELECT datepost, postname FROM postcase WHERE idpt = :idpt');
		$stmt->execute([':idpt' => $_idpt]);
		$row_postcase = $stmt->fetch(PDO::FETCH_ASSOC);

		# this cookie needs when post goes to bookcase or on shelves
		$cookie_name = 'datepost';
		$cookie_value = $row_postcase['datepost'];
		setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day

		$monthes = array(1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель', 5 => 'Май', 6 => 'Июнь', 7 => 'Июль',
							8 => 'Август', 9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь');

		$_year = date('Y', strtotime($row_postcase['datepost']));
		$_mnth = abs(date('m', strtotime($row_postcase['datepost'])));
		$_day = date('d', strtotime($row_postcase['datepost']));

		$postdate = $monthes[$_mnth] . ' ' . $_day . ', ' .  $_year;
		$postname = $row_postcase['postname'];

		return array('PostDate' => $postdate, 'PostName' => $postname);
	}

	private function addpost_tobcs($_idbc,  $_idpost) {
		if (isset($_COOKIE['datepost'])) {
			$_datepost = $_COOKIE['datepost'];
		} else {
			$_datepost = 0;
		}

		if ($_idpost != 0 and $_datepost != 0) {
			$_idbc = ltrim($_idbc, "bcid");

			try {
				$this->_dba->beginTransaction();

				$_stmt = $this->_dba->prepare('INSERT INTO postinbookcase (datepost, idpt, idbc) VALUES (?, ?, ?)');
				$_stmt->execute(array($_datepost, $_idpost, $_idbc));

				# commit the transaction
				$this->_dba->commit();

				$statement = $this->_dba->query('SELECT namebookcase FROM bookcase WHERE idbc = :idbc');
				$statement->execute([':idbc' => $_idbc]);
				$row_bookcase = $statement->fetch(PDO::FETCH_ASSOC);

				$_namebookcase = '<div id="addedbcid' . $_idbc . '"><small class="smallbookcase">' . $row_bookcase['namebookcase'] . '</small></div>';

				$echorespond = '[' . "'" . $_namebookcase  . "'" . ']';

			} catch (PDOException $e) {
				$this->_dba->rollBack();
				$echorespond = null;
			}
		} else {
			$echorespond = null;
		}

		return $echorespond;
	}

	private function deletepost_frombcs($_idbc,  $_idpost) {
		if ($_idpost != 0) {
			$_idbc = ltrim($_idbc, "bcid");
			try {
				$this->_dba->beginTransaction();

				$statement = $this->_dba->query('DELETE FROM postinbookcase WHERE idpt = :idpt  AND idbc = :idbc');
				$statement->execute(array(':idpt' => $_idpost, ':idbc' => $_idbc));

				# commit the transaction
				$this->_dba->commit();

				$_idbc = 'addedbcid' . $_idbc;
				$echorespond = '['  . "'" . $_idbc  . "'" . ']';
			} catch (PDOException $e) {
				$this->_dba->rollBack();
				$echorespond = null;
			}
		} else {
			$echorespond = null;
		}

		return $echorespond;
	}

	private function addpost_toshelve($_idshlv,  $_idpost) {
		if (isset($_COOKIE['datepost'])) {
			$_datepost = $_COOKIE['datepost'];
		} else {
			$_datepost = 0;
		}

		if ($_idpost != 0 and $_datepost != 0) {
			$_idshlv = ltrim($_idshlv, "shlvid");

			try {
				$this->_dba->beginTransaction();

				$_stmt = $this->_dba->prepare('INSERT INTO postonshelve (datepost, idpt, idsh) VALUES (?, ?, ?)');
				$_stmt->execute(array($_datepost, $_idpost, $_idshlv));

				# commit the transaction
				$this->_dba->commit();

				$statement = $this->_dba->prepare('SELECT nameshelve FROM shelves WHERE idsh = :idshlv');
				$statement->execute([':idshlv' => $_idshlv]);
				$row_shelve = $statement->fetch(PDO::FETCH_ASSOC);

				$_nameshelve = '<div id="addedshlvid' . $_idshlv . '"><small class="smallshelve">' . $row_shelve['nameshelve'] . '</small></div>';
				// $_idbc = 'bcid' . $_idbc;
				$echorespond = '[' . "'" . $_nameshelve  . "'" . ']';

			} catch (PDOException $e) {
				$this->_dba->rollBack();
				$echorespond = null;
			}
		} else {
			$echorespond = null;
		}

		return $echorespond;
	}

	private function deletepost_fromshelve($_idshlv,  $_idpost) {
		if ($_idpost != 0) {
			$_idshlv = ltrim($_idshlv, "shlvid");

			try {
				$this->_dba->beginTransaction();

				$statement = $this->_dba->query('DELETE FROM postonshelve WHERE idpt = :idpt  AND idsh = :idsh');
				$statement->execute(array(':idpt' => $_idpost, ':idsh' => $_idshlv));

				# commit the transaction
				$this->_dba->commit();

				$_idshlv = 'addedshlvid' . $_idshlv;
				$echorespond = '['  . "'" . $_idshlv  . "'" . ']';
			} catch (PDOException $e) {
				$this->_dba->rollBack();
				$echorespond = null;
			}
		} else {
			$echorespond = null;
		}

		return $echorespond;
	}

	# get Bookcases and Shelves
	private function get_bookcases($_idpost) {
		$arr_bookcase = array(); $i = 0;

		$statement = $this->_dba->query('SELECT idbc, namebookcase, aboutbookcase FROM bookcase');

		while($row_bookcase = $statement->fetch(PDO::FETCH_ASSOC)) {
			$_shelve = $this->get_shelves($_idpost, $row_bookcase['idbc']);

			$stmt = $this->_dba->prepare('SELECT idpt, idbc FROM postinbookcase WHERE idpt = :idpt AND idbc = :idbc');
			$stmt->execute(array(':idpt' => $_idpost, ':idbc' => $row_bookcase['idbc']));

			if ($stmt->fetch(PDO::FETCH_ASSOC)) {
				$_checked  = 'checked';
			} else {
				$_checked  = null;
			}

			$arr_bookcase[$i] = array('Record' => $row_bookcase['idbc'], 'Checked' => $_checked, 'NameBookcase' => $row_bookcase['namebookcase'], 'AboutBC' => $row_bookcase['aboutbookcase'], 'Shelve' => $_shelve);
			$i++;
		}

		return array('bookcase' => $arr_bookcase);
	}

	private function get_shelves($_idpost, $_idbc) {
		$str_shelves = null; $i = 0;

		$statement = $this->_dba->prepare('SELECT idsh, nameshelve FROM shelves WHERE idbc = :idbc');
		$statement->execute([':idbc' => $_idbc]);

		while($row_shelve = $statement->fetch(PDO::FETCH_ASSOC)) {
			$stmt = $this->_dba->prepare('SELECT idpt, idsh FROM postonshelve WHERE idpt = :idpt AND idsh = :idsh');
			$stmt->execute(array(':idpt' => $_idpost, ':idsh' => $row_shelve['idsh']));

			if ($stmt->fetch(PDO::FETCH_ASSOC)) {
				$_checked  = 'checked';
			} else {
				$_checked  = null;
			}

			$str_shelves  .= '<li><input type="checkbox" id="shlvid' . $row_shelve['idsh'].'" onchange="shelve_chechbox(this.id)" ' . $_checked . '>' . $row_shelve['nameshelve'] . '</li>';
		}

		return $str_shelves;
	}

	function get_title() {
		$_menu =$this->get_left_menu();
		$_title = array('title' => 'Тексты и Книги · Священник Яков Кротов');
		return array_merge($_title, $_menu);
	}

}
