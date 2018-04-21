<?php
/*
* Class Route get a request from pages.
* Calling controller Runs and model Bin.
*/
class Route
{
	static function start() {
		# Run and Action by default
		$run_name = 'Main';
		$action_name = 'index';

		$routes = explode('/', $_SERVER['REQUEST_URI']);

		# Get the name Controller
		if (!empty($routes[1])) {
			$run_name = $routes[1];
		}

		if (!empty($routes[2])) {
			$action_name = $routes[2];
		}
		
		unset($routes);
		# add prefix
		$bin_name = strtok('Bin_'.$run_name, '?');
		$run_name = strtok('Run_'.$run_name, '?');
		$action_name = strtok('action_'.$action_name, '?');

		# include a file the class Bin
		$bin_file = strtolower($bin_name).'.php';
		$bin_path = 'etc/bin/'.$bin_file;

		if (file_exists($bin_path)) {
			include 'etc/bin/'.$bin_file;
		}

		# include a file the class Run
		$run_file = strtolower($run_name).'.php';
		$run_path = 'etc/run/'.$run_file;

		if (file_exists($run_path)) {
			include 'etc/run/'.$run_file;

			# creat Run
			$run = new $run_name;
			$action = $action_name;

			if (method_exists($run, $action)) {
				# call the action Run
				$run->$action();
			} else {
				Route::ErrorPage404();
			}
		} else {
			Route::ErrorPage404();
		}
  }

	private static function ErrorPage404() {
		$host = 'https://'.$_SERVER['HTTP_HOST'].'/';
    header('HTTP/2.0 404 Not Found');
		# header("Status: 404 Not Found");
		header('Location: '.$host.'404.html');
		exit;
	}
}
