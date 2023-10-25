<?php

require_once __DIR__ . '/vendor/autoload.php';

use Google\CloudFunctions\FunctionsFramework;
use Psr\Http\Message\ServerRequestInterface;

FunctionsFramework::http('run', 'run');

function run(ServerRequestInterface $request): string
{	
	$env = getenv('env');
	if (!$env) {
		Logger::error("no env set");
		return '';
	}

	$config = Config::load(__DIR__ . "/config.ini", $env);

	$body = json_decode($request->getBody()->getContents(), true);
	$resource_url = $body["resource_url"] ?? null;

	if (!$resource_url) {
		Logger::error("no resource_url");
		return '';
	}

	$secret = new Secret(
		$config['project_id'], 
		$config['shipstation_secret_id'], 
		$config['shipstation_secret_version']
	);

	$shipstation_client = new ShipStation($secret);
	$resource = $shipstation_client->getResource($resource_url);
	$orders = $resource['orders'] ?? [];
	if (!$orders) {
		Logger::error("no orders");
		return '';
	}

	foreach ($orders as $order) {
		$original_note = $order['customerNotes'] ?? "";
		$parser = new NoteParser();
		$parser->parse($original_note);
		$order['customerNotes'] = $parser->getNote();
		$order['advancedOptions']['customField1'] = $parser->getExtra();
		$shipstation_client->createOrUpdateOrder($order);
	}

	Logger::info(
		"success", 
		["count" => count($orders)]
	);

	return "ok";
}

