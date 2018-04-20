<?php
class Run_Shelve extends Run
{
	function __construct() {
		$this->bin = new Bin_Shelve();
		$this->view = new View();
	}

	function action_index() {
		$default_page = 1;

		$_idsh = isset($_GET['idsh']) ? $_GET['idsh'] : $default_page;
		$_idsh = number_format(abs($_idsh));

		$_page = isset($_GET['next']) ? $_GET['next'] : $default_page;
		$_page = number_format(abs($_page));

		$data = $this->bin->get_data($_idsh, $_page);
		$title = $this->bin->get_title($_idsh);

		$this->view->generate('shelve_view.php', 'template_view.php', $data, $title);
	}
}
