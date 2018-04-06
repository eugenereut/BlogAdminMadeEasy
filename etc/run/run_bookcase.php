<?php
class Run_Bookcase extends Run
{
	function __construct() {
		$this->bin = new Bin_Bookcase();
		$this->view = new View();
	}

	function action_index() {
		$data = $this->bin->get_data();
		$title = $this->bin->get_title();

		$this->view->generate('bookcase_view.php', 'template_view.php', $data, $title);
	}
}
