<?php
	///////////////////////////////////////
	///// Script for Determine apps routines optimization
	///// Started on 30.04.2018
	///// Author: Alexander Kuzmenko aka F3desha (skype: kas_illuzion)
	//////////////////////////////////////

	const ROOT_DIR = __DIR__;
	const PATH_TO_APPS = 'www'.DIRECTORY_SEPARATOR.'workspace'.DIRECTORY_SEPARATOR.'apps';
	$bootstrap = false;
	$options = [];
	$flags = [];

	//form input
	foreach ($argv as $i=>$arg )
	{
		//1. If param isnt flag or option - its function name
		$arg[0] !== '-' && substr($arg, -4) !== '.php'
			? $bootstrap = $arg : null;

		//2. If param is option - send it to options array
		if($arg[0] === '-' && $arg[1] !== '-')
		{
			$option = explode('=', $arg);
			$option[0] = ltrim($option[0], '-');
			$options[$option[0]] = $option[1];
		}

		//3. If param is flag - send it to flags array
		if($arg[0] === '-' && $arg[1] === '-' && $arg[2] !== '-')
		{
			$flag = explode('=', $arg);
			$flag[0] = ltrim($flag[0], '-');
			$flags[$flag[0]] = $flag[1];
		}
	}

	function git_checkout($options, $flags){
		$all_modules = directories_on_path(PATH_TO_APPS);

		if($all_modules){
			foreach ($all_modules as $i=>$module_directory){
				chdir(ROOT_DIR);
				chdir(PATH_TO_APPS);
				chdir($module_directory);
				//We are now in modules directory

				//Get list of all branches
				$branches_list = [];
				exec('git branch', $i);
				$dirty_branches_list = $i;

				//Get active branch
				$pristine_branches_list = [];
				foreach ($dirty_branches_list as $branch){
					if($branch[0] === '*' && $branch[1] === ' '){
						$active_branch = ltrim($branch, '* ');
						$pristine_branches_list[] = $active_branch;
					} else {
						$pristine_branches_list[] = trim($branch);
					}
				}

				echo $module_directory.":\n";

				//If branch option exists, check if it exists in local git
				if(array_key_exists('branch', $options)){
					if(in_array($options['branch'], $pristine_branches_list)){
						//Option branch already exists. No need to create new branch
						$checkout_command = 'git checkout '.$options['branch'];
					} else {
						//Create new branch. Get the name from branch
						$checkout_command = 'git checkout -b '.$options['branch'].' origin/'.$options['branch'];
					}
					exec($checkout_command);
				} elseif(!array_key_exists('branch', $options)){
					echo 'Enter "-branch=$branch_name" option for function running properly'."\n";
				}
			}
		}
	}

	function directories_on_path($path){
		$dirs = array();
		// directory handle
		$dir = dir($path);

		while (false !== ($entry = $dir->read())) {
			if ($entry != '.' && $entry != '..') {
				if (is_dir($path . DIRECTORY_SEPARATOR .$entry)) {
					$dirs[] = $entry;
				}
			}
		}
		return $dirs;
	}

	if($bootstrap){
		switch ($bootstrap){
			case 'git_checkout':
				git_checkout($options, $flags);
				break;
			default:
				echo 'Function '.$bootstrap.' not found.';
				break;
		}
	} else {
		echo 'Please enter a function you want F3desha to run.';
	}