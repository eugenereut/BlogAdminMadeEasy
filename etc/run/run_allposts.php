<?php
class Run_Allposts extends Run
{
	function __construct() {
		$this->bin = new Bin_Allposts();
		$this->view = new View();
	}

	function action_index() {
		$data = $this->bin->get_data($_idpt = null);
		$title = $this->bin->get_title($_idbc = null);

		$this->view->generate('allposts_view.php', 'template_view.php', $data, $title);
	}
}
