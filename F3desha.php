<?php
	namespace F3desha;

	include 'Classes/Console.php';
	include 'Classes/Location.php';
	include 'Classes/Git.php';
	include 'Classes/Printer.php';

	use F3desha\Classes\Location;
	use F3desha\Classes\Console;

	class F3desha {
		public function __construct($argv){
			//Create console instance and parse passed arguments
			$console = new Console($argv);

			//Receive location routes config based on [LOCATION_ALIAS]
			$console->commands[0][0] === '['
				? $location_config = new Location($console->commands[0])
				: die("No [Location Alias] found. Please check your command\n");

			//Go to location target
			$folders_to_run_module = [];
			if($location_config->current_path['single_path']){
				$folders_to_run_module['trunk'] = $location_config->current_path['directory'];
			} else {
				foreach ($location_config->directories_on_path() as $path){
					$folders_to_run_module[$path] = $location_config->current_path['directory'].DIRECTORY_SEPARATOR.$path;
				}
			}

			foreach ($folders_to_run_module as $module_name => $folder_to_run_module){
				$location_config->change_directory($folder_to_run_module);

				//Create f3desha module based on params
				$f3desha_module = "\\F3desha\Classes\\".ucfirst($console->commands[1]);
				$f3desha_module = new $f3desha_module($module_name, $console);

				//Execute f3desha modules method based on params
				$method_name = $console->commands[2];
				$f3desha_module->{$method_name}();
			}
		}
	}

	$init = new F3desha($argv);






