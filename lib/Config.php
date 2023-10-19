<?php 

class Config
{
	private static $instances = [];

	private array $config = [];

	private function __construct($file_path)
	{
		if (!file_exists($file_path)) {
			throw new Exception("file path does not exist: $file_path");
		}

		$this->config = parse_ini_file($file_path);
	}

	public static function get($file_path)
	{
		$key = md5($file_path);

		if (!isset(self::$instances[$key])) {
			self::$instances[$key] = new self($file_path);
		}

		return self::$instances[$key]->config;
	}
}