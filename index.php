<?php

require_once __DIR__ . '/vendor/autoload.php';

use Google\CloudFunctions\FunctionsFramework;
use Psr\Http\Message\ServerRequestInterface;

FunctionsFramework::http('run', 'run');

function run(ServerRequestInterface $request): string
{	
	$config = Config::load(__DIR__ . "/config.ini");

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

	$shipstation_client = new ShipstationClient($secret);
	$resource = $shipstation_client->getResource($resource_url);
	$orders = $resource['orders'] ?? [];
	if (!$orders) {
		Logger::error("no orders");
		return '';
	}

	foreach ($orders as $order) {
		$original_note = $order['customerNotes'] ?? "";
		$note_parts = explode("<br/>", $original_note, 2);
		$note = $note_parts[0] ?? "";
		$extra = $note_parts[1] ?? "";

		if ($note == "null") {
			$note = "";
		}

		$order['customerNotes'] = $note;
		$order['advancedOptions']['customField1'] = $extra;
		$shipstation_client->createOrUpdateOrder($order);
	}

	Logger::info(
		"success", 
		["count" => count($orders)]
	);

	return "ok";
}

