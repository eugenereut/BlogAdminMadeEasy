<?php
class Run_Main extends Run
{
	function __construct() {
		$this->bin = new Bin_Main();
		$this->view = new View();
	}

	function action_index() {
		$data = $this->bin->get_data();
		$title = $this->bin->get_title();

		$this->view->generate('main_view.php', 'template_view.php', $data, $title);
	}
}
