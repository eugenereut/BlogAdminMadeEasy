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


	function get_data($_idpt = NULL) {
		return $this->get_post();
	}

	private function get_post() {
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

		return array('Allposts' => $arr_posts);
	}

	function get_title($_id) {
		$_menu =$this->get_left_menu($_id);
		$_title = array('title' => 'Тексты и Книги · Священник Яков Кротов');
		return array_merge($_title, $_menu);
	}

}
