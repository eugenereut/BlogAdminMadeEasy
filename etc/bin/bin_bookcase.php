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
	}

	function get_title($_id) {
		$_menu =$this->get_left_menu($_id);
		$_title = array('title' => 'Тексты и Книги · Священник Яков Кротов');
		return array_merge($_title, $_menu);
	}

}
