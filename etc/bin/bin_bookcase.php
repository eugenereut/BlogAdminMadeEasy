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


	function get_data($_idbc = NULL) {
		return $this->get_postinbookcase($_idbc);
	}

	private function get_postinbookcase($_idbc) {
		$arr_posts = array(); $i = 0;

		$_stmt = $this->_dba->prepare('SELECT idpt FROM postinbookcase WHERE idbc = :idbc ORDER BY datepost DESC');
		$_stmt->execute([':idbc' => $_idbc]);

		while($_bookcase = $_stmt->fetch(PDO::FETCH_ASSOC)) {
			$_posts = $this->get_posts($_bookcase['idpt']);

			$arr_posts[$i] = array('PostID' => $_bookcase['idpt'], 'PostDate' => $_posts[0], 'PostName' => $_posts[1], 'PostBody' => $_posts[2]);
			$i++;
		}

		return array('Posts' => $arr_posts);
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

		for ($i=0; $i < $j; $i++) {
			if (isset($routes[$i])) {
				$_postbody .= $routes[$i];
			}
		}

		return $_postbody;
	}

	function get_title($_id) {
		$_menu =$this->get_left_menu($_id);
		$_title = array('title' => 'Тексты и Книги · Священник Яков Кротов');
		return array_merge($_title, $_menu);
	}

}
