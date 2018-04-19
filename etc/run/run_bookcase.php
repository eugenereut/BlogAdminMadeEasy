<?php
class Run_Bookcase extends Run
{
	function __construct() {
		$this->bin = new Bin_Bookcase();
		$this->view = new View();
	}

	function action_index() {
		$_idbc = $_GET['idbc'];

		$default_page = 1;

		$_page = isset($_GET['next']) ? $_GET['next'] : $default_page;
		$_page = number_format(abs($_page));

		$data = $this->bin->get_data($_idbc, $_page);
		$title = $this->bin->get_title($_idbc);

		$this->view->generate('bookcase_view.php', 'template_view.php', $data, $title);
	}
}
