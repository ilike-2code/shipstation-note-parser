<?php 

class Logger 
{
	const INFO = "info";
	const ERROR = "error";

	private static $file = null;

	public static function info(string $msg, array $context = []): void
	{
		self::log($msg, $context, self::INFO);		
	}

	public static function error(string $msg, array $context = []): void
	{
		self::log($msg, $context, self::ERROR);		
	}

	private static function log(string $msg, array $context, string $level): void
	{
		$context['message'] = $msg;
		$context['severity'] = $level;

		fwrite(self::getFile(), json_encode($context) . PHP_EOL);
	}

	private static function getFile()
	{
		if (!self::$file) {
			self::$file = fopen('php://stderr', 'wb');
		}

		return self::$file;
	}
}
