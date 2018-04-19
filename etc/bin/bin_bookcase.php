<?php
/*
* bin_main.php - model bookcase
*
* maintained by:  Eugene Reut <eugene.reut@gmail.com>
* Please ALWAYS copy eugene.reut@gmail.com
* on emails.
*
*/
class Bin_Bookcase extends Bin
{

	public $_dba;

	function __construct() {
		$this->_dba = $this->db_access();
	}

	# Model show all posts added only to bookcase, if no post there then get posts from shelves
	function get_data($_idbc = null, $_listing = null) {
		# this cookie needs for left menu
		$cookie_name = 'idbc';
		$cookie_value = $_idbc;
		setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day

		$_topshelves_menu = $this->get_topshelves_menu($_idbc, $_idsh = null);
		$_postinbookcase = $this->get_postinbookcase($_idbc, $_listing);
		return array_merge($_topshelves_menu, $_postinbookcase);
	}

	private function get_postinbookcase($_idbc, $_listing) {
		$arr_posts = array(); $_all_posts = array(); $i = 0;

		$_stmt = $this->_dba->prepare('SELECT idpt FROM postinbookcase WHERE idbc = :idbc ORDER BY datepost DESC');
		$_stmt->execute([':idbc' => $_idbc]);

		while($_bookcase = $_stmt->fetch(PDO::FETCH_ASSOC)) {
			$_posts = $this->get_posts($_bookcase['idpt']);

			$_bc_names = $this->get_addedname_bookcases($_bookcase['idpt']);
			$_sh_names = $this->get_addedname_shelves($_bookcase['idpt']);
			$_sort_eachother = $this->sortshelves_tobookcases($_bc_names, $_sh_names);

			$arr_posts[$i] = array('PostID' => $_bookcase['idpt'], 'PostDate' => $_posts[0], 'PostName' => $_posts[1], 'PostBody' => $_posts[2], 'PostBcSh' => $_sort_eachother);
			# this array for the right modal window where all posts names
			$_all_posts[$i] = array('PostID' => $_bookcase['idpt'], 'PostName' => $_posts[1]);

			++$i;
		}

		$_bcstmt = $this->_dba->prepare('SELECT namebookcase FROM bookcase WHERE idbc = :idbc');
		$_bcstmt->execute([':idbc' => $_idbc]);

		$_nbc = $_bcstmt->fetch(PDO::FETCH_ASSOC);
		$_nbc = $_nbc['namebookcase'];

		$_all_posts = array('All_inmodal_window' => $_all_posts, 'BookcaseName' => $_nbc);

		$arr_posts = $this->pagination_posts($i, $arr_posts, $_idbc, $_listing);

		return array_merge($arr_posts, $_all_posts);
	}

	private function get_posts($_idpt) {
		$stmt = $this->_dba->prepare('SELECT datepost, postname, filepath FROM postcase WHERE idpt = :idpt');
		$stmt->execute([':idpt' => $_idpt]);
		$_postcase = $stmt->fetch(PDO::FETCH_ASSOC);

		$monthes = array(1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель', 5 => 'Май', 6 => 'Июнь', 7 => 'Июль',
							8 => 'Август', 9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь');

		$_year = date('Y', strtotime($_postcase['datepost']));
		$_mnth = abs(date('m', strtotime($_postcase['datepost'])));
		$_day = date('d', strtotime($_postcase['datepost']));

		$postdate = $monthes[$_mnth] . ' ' . $_day . ', ' .  $_year;
		$postname = $_postcase['postname'];
		$postbody = $this->get_content_file($_postcase['filepath']);

		return array($postdate, $postname, $postbody);
	}

	private function get_content_file($_filepath) {
		$_postbody = file_get_contents($_filepath);
		$routes = explode('</p>', $_postbody);
		$_postbody =null;

		$j = rand(3, 5);

		for ($i=0; $i < $j; ++$i) {
			if (isset($routes[$i])) {
				$_postbody .= $routes[$i];
			}
		}

		return $_postbody;
	}

	private function pagination_posts($_i, $arr_posts, $_idbc, $_listing) {
		if ($_i > 0) {
			$_arr_posts_short = array_chunk($arr_posts, 7);
			# how much pages in array with 7 elements
			$_pages = count($_arr_posts_short);

			# check what comes from controller
			if ($_listing > $_pages) {
				$_listing = 1;
			} elseif ($_listing <= 0) {
				$_listing = 1;
			}

			$arr_posts = $_arr_posts_short[$_listing - 1];
		} else {
			$_listing = 1;  $_pages = 1;
		}

    $page_active = array('active_page' => $_listing, 'entries' => $_i, 'iDbc' => $_idbc, 'pages' => $_pages, 'Posts' => $arr_posts);

		return $page_active;
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
