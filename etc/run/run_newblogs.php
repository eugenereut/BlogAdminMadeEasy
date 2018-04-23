<?php
class Run_Newblogs extends Run
{
	function __construct() {
		$this->bin = new Bin_Newblogs();
		$this->view = new View();
	}

	function action_index() {
		$default_page = 1;

		$_page = isset($_GET['next']) ? $_GET['next'] : $default_page;
		$_page = number_format(abs($_page));

		$data = $this->bin->get_data($_idpt = null, $_page);
		$title = $this->bin->get_title($_idbc = 'new');

		$this->view->generate('newblogs_view.php', 'template_view.php', $data, $title);
	}
}
