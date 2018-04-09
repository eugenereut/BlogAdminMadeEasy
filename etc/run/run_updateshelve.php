<?php
class Run_Updateshelve extends Run
{
	function __construct() {
		$this->bin = new Bin_Updateshelve();
		$this->view = new View();
	}

	function action_index() {
		$_idsh = $_GET['idsh'];
		$data = $this->bin->get_data($_idsh);
		$title = $this->bin->get_title();

		$this->view->generate('updateshelve_view.php', 'template_view.php', $data, $title);
	}
}
