<?php
class Run_Post extends Run
{
	function __construct() {
		$this->bin = new Bin_Post();
		$this->view = new View();
	}

	function action_index() {
		$_idpt = $_GET['idpt'];
		$data = $this->bin->get_data($_idpt);
		$title = $this->bin->get_title($_idbc = null);

		$this->view->generate('post_view.php', 'template_view.php', $data, $title);
	}
}
