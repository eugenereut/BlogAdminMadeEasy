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

		return array('PostDate' => $postdate, 'PostName' => $row_postcase['postname'], 'PostBody' => $postbody);
	}

	function get_title($_id) {
		$_menu =$this->get_left_menu($_id);
		$_title = array('title' => 'Тексты и Книги · Священник Яков Кротов');
		return array_merge($_title, $_menu);
	}

}
