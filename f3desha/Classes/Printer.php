<?php

	namespace F3desha\Classes;

	class Printer
	{

		public static function colorEcho($string, $color = "0;37")
		{
			return "\033[".$color."m".$string."\033[0m";
		}
	}