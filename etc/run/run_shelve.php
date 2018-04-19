<?php
class Run_Shelve extends Run
{
	function __construct() {
		$this->bin = new Bin_Shelve();
		$this->view = new View();
	}

	function action_index() {
		$_idsh = $_GET['idsh'];

		$data = $this->bin->get_data($_idsh);
		$title = $this->bin->get_title($_idsh);

		$this->view->generate('shelve_view.php', 'template_view.php', $data, $title);
	}
}
