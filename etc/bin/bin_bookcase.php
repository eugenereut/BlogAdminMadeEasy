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

	public $_dbc;

	function __construct() {
		$this->_dbc = $this->db_access();
	}


	function get_data() {
	}

	function get_title() {
		return array('title' => 'Тексты и Книги · Священник Яков Кротов');
	}

}
