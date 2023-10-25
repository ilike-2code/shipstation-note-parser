<?php 

class Config
{
	private static $instance = null;
	private array $config = [];

	private function __construct(string $file_path, string $env)
	{
		if (!file_exists($file_path)) {
			throw new Exception("file path does not exist: $file_path");
		}

		$raw_config = parse_ini_file($file_path, true);
		if (array_key_exists(!$env, $raw_config)) {
			throw new Exception("config for [$env] does not exist");	
		}

		$this->config = $raw_config[$env];
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
