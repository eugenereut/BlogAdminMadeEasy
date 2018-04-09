<?php
class Run_Writing extends Run
{
	function __construct() {
		$this->bin = new Bin_Writing();
		$this->view = new View();
	}

	function action_index() {
		$data = $this->bin->get_data($_id = NULL);
		$title = $this->bin->get_title();

		$this->view->generate('writing_view.php', 'template_view.php', $data, $title);
	}
}
