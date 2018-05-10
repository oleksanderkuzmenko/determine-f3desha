<?php

	namespace F3desha\Classes;
	class Console
	{
		const CONSOLE_LIGHT_BLUE = '1;34';
		const CONSOLE_GREEN = '0;32';

		public $options;
		public $flags;
		public $commands;

		public function __construct($argv)
		{
			$options = [];
			$flags = [];

			foreach ($argv as $i=>$arg )
			{
				//1. If param isnt flag or option - its command name
				$arg[0] !== '-' && substr($arg, -4) !== '.php'
					? $commands[] = $arg : null;

				//2. If param is option - send it to options array
				if($arg[0] === '-' && $arg[1] !== '-')
				{
					if (strpos($arg, '=') !== false){
						$option = explode('=', $arg);
						$option[0] = ltrim($option[0], '-');
						$options[$option[0]] = $option[1];
					} else {
						$options[ltrim($arg, '-')] = true;
					}
				}

				//3. If param is flag - send it to flags array
				if($arg[0] === '-' && $arg[1] === '-' && $arg[2] !== '-')
				{
					if (strpos($arg, '=') !== false){
						$flag = explode('=', $arg);
						$flag[0] = ltrim($flag[0], '-');
						$flags[$flag[0]] = $flag[1];
					} else {
						$flags[ltrim($arg, '-')] = true;
					}
				}
			}
			$this->options = $options;
			$this->flags = $flags;
			$this->commands = $commands;
		}
	}