<?php
class Run_Setuppost extends Run
{
	function __construct() {
		$this->bin = new Bin_Setuppost();
		$this->view = new View();
	}

	function action_index() {
		if (isset($_GET['idpt'])) {
			$_idpost = $_GET['idpt'];
		} else {
			if (isset($_COOKIE['idpost'])) {
				$_idpost = $_COOKIE['idpost'];
			} else {
				$_idpost = 0;
			}
		}

		$data = $this->bin->get_data($_idpost);
		$title = $this->bin->get_title();

		$this->view->generate('setuppost_view.php', 'template_view.php', $data, $title);
	}
}
