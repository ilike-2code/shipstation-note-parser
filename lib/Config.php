<?php 

class Config
{
	private static $instance = null;
	private array $config = [];

	private function __construct($file_path)
	{
		if (!file_exists($file_path)) {
			throw new Exception("file path does not exist: $file_path");
		}

		$this->config = parse_ini_file($file_path);
	}

	public static function load($file_path)
	{
		self::$instance = new self($file_path);
		return self::get();
	}

	public static function get()
	{
		if (!self::$instance) {
			throw new Exception("no config loaded");
		}

		return self::$instance->config;
	}
}
