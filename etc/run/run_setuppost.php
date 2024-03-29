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

			$cookie_name = 'idpost';
			$cookie_value = $_idpost;
			setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day

		} else {
			if (isset($_COOKIE['idpost'])) {
				$_idpost = $_COOKIE['idpost'];
			} else {
				$_idpost = 0;
			}
		}

		$data = $this->bin->get_data($_idpost, $_listing = null);
		$title = $this->bin->get_title();

		$this->view->generate('setuppost_view.php', 'template_view.php', $data, $title);
	}

	function action_editpost() {
		if (isset($_GET['idpt'])) {
			$_idpost = $_GET['idpt'];
		} else {
			if (isset($_COOKIE['idpost'])) {
				$_idpost = $_COOKIE['idpost'];
			} else {
				$_idpost = 0;
			}
		}

		$data = $this->bin->edit_post($_idpost);
		$title = $this->bin->get_title();

		$this->view->generate('editpost_view.php', 'template_view.php', $data, $title);
	}

	function action_deletepost() {
		if (isset($_GET['idpt'])) {
			$_idpost = $_GET['idpt'];
		} else {
			if (isset($_COOKIE['idpost'])) {
				$_idpost = $_COOKIE['idpost'];
			} else {
				$_idpost = 0;
			}
		}

		$data = $this->bin->delete_post($_idpost);
		$title = $this->bin->get_title();

		$this->view->generate('deletepost_view.php', 'template_view.php', $data, $title);
	}
}
