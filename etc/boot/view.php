<?php
# class View display Users Data
class View {
	public $template_view;

	public function generate($content_view, $template_view, $data = null, $title = null) {
		include 'etc/views/'.$template_view;
	}
}
