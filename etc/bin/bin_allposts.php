<?php
/*
* bin_main.php - model allposts
*
* maintained by:  Eugene Reut <eugene.reut@gmail.com>
* Please ALWAYS copy eugene.reut@gmail.com
* on emails.
*
*/
class Bin_Allposts extends Bin
{

	public $_dba;

	function __construct() {
		$this->_dba = $this->db_access();
	}


	function get_data($_idpt = null, $_listing = null) {
		return $this->get_post($_listing);
	}

	private function get_post($_listing) {
		$arr_posts = array(); $i = 0;

		$monthes = array(1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель', 5 => 'Май', 6 => 'Июнь', 7 => 'Июль',
							8 => 'Август', 9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь');

		$stmt = $this->_dba->query('SELECT idpt, datepost, postname FROM postcase ORDER BY datepost DESC');

		while($row_postcase = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$_year = date('Y', strtotime($row_postcase['datepost']));
			$_mnth = abs(date('m', strtotime($row_postcase['datepost'])));
			$_day = date('d', strtotime($row_postcase['datepost']));

			$postdate = $monthes[$_mnth] . ' ' . $_day . ', ' .  $_year;

			$arr_posts[$i] = array('Record' => $row_postcase['idpt'], 'PostDate' => $postdate, 'PostName' => $row_postcase['postname']);
			$i++;
		}

		# $_size_arr = 14, if changed here go to allposts_view.php and change there
		$arr_posts = $this->pagination_posts($i, $arr_posts, $_listing, 14);

		return $arr_posts;
	}

	private function pagination_posts($_i, $arr_posts, $_listing, $_size_arr) {
		if ($_i > 0) {
			$_arr_posts_short = array_chunk($arr_posts, $_size_arr);
			# how much elements with $_size_arr
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

    $page_active = array('active_page' => $_listing, 'entries' => $_i, 'pages' => $_pages, 'Posts' => $arr_posts);

		return $page_active;
	}

	function get_title($_id) {
		$_menu =$this->get_left_menu($_id = null);
		$_title = array('title' => 'Тексты и Книги · Священник Яков Кротов');
		return array_merge($_title, $_menu);
	}

}
