<?php
	namespace F3desha\Classes;

	include 'Interfaces/iAllowable.php';

	use iAllowable;
	use F3desha\Classes\Printer;

	class Git implements iAllowable
	{

		public $console;
		public $module_name;

		public function __construct($module_name, $console)
		{
			$this->module_name = $module_name;
			$this->console = $console;
			$this->filter_params();
		}

		public function filter_params()
		{
			$allowed['commands'] = [
				'git',
				'checkout',
				'branch',
				'pull',
				'[APPS]',
				'[TRUNK]',
				'[APPLI]'
			];
			$allowed['flags'] = [

			];
			$allowed['options'] = [
				'app',
				'D',
				'l',
				'r',
				'branch',
				'reset'
			];

			foreach ($this->console as $type=>$params_set){
				foreach ($params_set as $key=>$set){
					switch ($type){
						case 'commands':

								if(!in_array($set, $allowed['commands'])){
									echo Printer::colorEcho($set.': unknown param in commands');
									die();
								}

							break;
						case 'options':

								if(!in_array($key, $allowed['options'])){
									echo Printer::colorEcho($set.': unknown param in options');
									die();
								}

							break;
						case 'flags':

								if(!in_array($key, $allowed['flags'])){
									echo Printer::colorEcho($set.': unknown param in flags');
									die();
								}

							break;
					}
				}
			}

		}

		public function branch(){
			$show_only = false;
			$show_remotes='';
			array_key_exists('app', $this->console->options)
				? $show_only = $this->console->options['app'] : null;
			array_key_exists('r', $this->console->options) ? $show_remotes = ' -a' : null;


					$branches_list = [];
					exec('git branch'.$show_remotes, $i);
					$dirty_branches_list = $i;

					//Get active branch
					$pristine_branches_list = [];
						foreach ($dirty_branches_list as $branch) {
							if ($branch[0] === '*' && $branch[1] === ' ') {
								$active_branch = ltrim($branch, '* ');
								$pristine_branches_list[] = $active_branch;
								if(!$show_only || $show_only === $this->module_name){
									echo Printer::colorEcho($this->module_name, Console::CONSOLE_LIGHT_BLUE).': on branch ' . Printer::colorEcho($active_branch, Console::CONSOLE_GREEN)."\n";
								}
							} else {
								$pristine_branches_list[] = trim($branch);
							}
						}
						if (array_key_exists('l', $this->console->options)) {
							foreach ($pristine_branches_list as $branch_l) {
								if(!$show_only || $show_only === $this->module_name) {
									echo Printer::colorEcho('- ' . $branch_l) . "\n";
								}
							}
						}
						if (array_key_exists('D', $this->console->options)) {
							foreach ($pristine_branches_list as $branch_l) {
								if(!$show_only || $show_only === $this->module_name) {
									if($branch_l !== $active_branch){
										echo ' - deleting branch: '.Printer::colorEcho($branch_l, Console::CONSOLE_RED)."\n";
										$checkout_command = 'git branch -D '.$branch_l;
										exec($checkout_command);
									}
								}
							}
						}


		}

		public function checkout()
		{
			$show_only = false;
			array_key_exists('app', $this->console->options)
				? $show_only = $this->console->options['app'] : null;
			//We are now in modules directory
			//Get list of all branches
			$branches_list = [];
			exec('git branch', $i);
			$dirty_branches_list = $i;
			//Get active branch
			$pristine_branches_list = [];
			foreach ($dirty_branches_list as $branch) {
				if ($branch[0] === '*' && $branch[1] === ' ') {
					$active_branch = ltrim($branch, '* ');
					$pristine_branches_list[] = $active_branch;
				} else {
					$pristine_branches_list[] = trim($branch);
				}
			}

			//If branch option exists, check if it exists in local git
			if (array_key_exists('branch', $this->console->options)) {
				if(!$show_only || $show_only === $this->module_name){
					echo $this->module_name . ":\n";
					if (in_array($this->console->options['branch'], $pristine_branches_list)) {
						//Option branch already exists. No need to create new branch
						$checkout_command = 'git checkout ' . $this->console->options['branch'];
					} else {
						//Create new branch. Get the name from branch
						$checkout_command = 'git checkout -b '.$this->console->options['branch'].' origin/' . $this->console->options['branch'];
					}
					exec($checkout_command);
				}
			} elseif(array_key_exists('reset', $this->console->options)){
				$checkout_command = 'git checkout . ';
				exec($checkout_command);
			} elseif (!array_key_exists('branch', $this->console->options)) {
				echo Printer::colorEcho('Enter "-branch=$branch_name" option for function running properly') . "\n";
			}
		}

		public function pull()
		{
			$show_only = false;
			array_key_exists('app', $this->console->options)
				? $show_only = $this->console->options['app'] : null;
			//We are now in modules directory
			//Get list of all branches
			$branches_list = [];
			exec('git branch', $i);
			$dirty_branches_list = $i;
			//Get active branch
			foreach ($dirty_branches_list as $branch) {
				if ($branch[0] === '*' && $branch[1] === ' ') {
					$active_branch = ltrim($branch, '* ');
				}
			}

			//If branch option exists, check if it exists in local git

				if(!$show_only || $show_only === $this->module_name){
					echo Printer::colorEcho($this->module_name, Console::CONSOLE_LIGHT_BLUE) . ":\n";

						//Option branch already exists. No need to create new branch
						$checkout_command = 'git pull origin ' . $active_branch;

					exec($checkout_command);
				}

		}
	}