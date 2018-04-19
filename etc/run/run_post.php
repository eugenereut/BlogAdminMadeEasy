<?php
class Run_Post extends Run
{
	function __construct() {
		$this->bin = new Bin_Post();
		$this->view = new View();
	}

	function action_index() {
		$_idpt = $_GET['idpt'];

		# this cookie needs for left menu
		if(isset($_COOKIE['idbc'])) {
			$_idbc = $_COOKIE['idbc'];
		} else {
			$_idbc = null;
		}

		$data = $this->bin->get_data($_idpt);
		$title = $this->bin->get_title($_idbc);

		$this->view->generate('post_view.php', 'template_view.php', $data, $title);
	}
}
