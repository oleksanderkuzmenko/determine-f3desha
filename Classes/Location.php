<?php

	namespace F3desha\Classes;
	class Location
	{
		const WWW = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'www';

		private $paths = [
			'[APPS]' => [
				'directory' => self::WWW.DIRECTORY_SEPARATOR.'workspace'.DIRECTORY_SEPARATOR.'apps',
				'single_path' => false
			],
			'[APPLI]' => [
				'directory' => self::WWW.DIRECTORY_SEPARATOR.'workspace'.DIRECTORY_SEPARATOR.'appli',
				'single_path' => false
			],
			'[TRUNK]' => [
				'directory' => self::WWW.DIRECTORY_SEPARATOR.'workspace'.DIRECTORY_SEPARATOR.'trunk',
				'single_path' => true
			]
		];
		public $current_path;

		public function __construct($path)
		{
			$this->current_path = array_key_exists($path, $this->paths)
				? $this->paths[$path]
				: false;
		}

		public function change_directory($path)
		{
			chdir($path);
		}

		public function directories_on_path()
		{
			$dirs = array();
			// directory handle
			$dir = dir($this->current_path['directory']);

			while (false !== ($entry = $dir->read())) {
				if ($entry != '.' && $entry != '..') {
					if (is_dir($this->current_path['directory'] . DIRECTORY_SEPARATOR .$entry)) {
						$dirs[] = $entry;
					}
				}
			}
			return $dirs;
		}
	}