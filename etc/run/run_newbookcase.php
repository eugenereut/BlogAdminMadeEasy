<?php
class Run_Newbookcase extends Run
{
	function __construct() {
		$this->bin = new Bin_Newbookcase();
		$this->view = new View();
	}

	function action_index() {
		$data = $this->bin->get_data($_id = NULL);
		$title = $this->bin->get_title();

		$this->view->generate('newbookcase_view.php', 'template_view.php', $data, $title);
	}
}
