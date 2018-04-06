<?php
# class Run is Controlling user request
class Run {
	public $bin;
	public $view;

	function __construct() {
		$this->view = new View();
	}

	# call (action), by default
	private function action_index() {}
}
