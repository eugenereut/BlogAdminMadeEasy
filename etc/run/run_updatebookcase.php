<?php
class Run_Updatebookcase extends Run
{
	function __construct() {
		$this->bin = new Bin_Updatebookcase();
		$this->view = new View();
	}

	function action_index() {
		$_idbc = $_GET['idbc'];
		$data = $this->bin->get_data($_idbc);
		$title = $this->bin->get_title();

		$this->view->generate('updatebookcase_view.php', 'template_view.php', $data, $title);
	}
}
