<?php 

use Google\Cloud\SecretManager\V1\SecretManagerServiceClient;

class Secret
{
	private $value;
	private $key;
	private $project_id;
	private $secret_id; 
	private $version;

	public function __construct($project_id, $secret_id, $version)
	{
		$this->project_id = $project_id;
		$this->secret_id = $secret_id;
		$this->version = $version;
	}

	public function getKey(): string
	{
		return sprintf(
			'projects/%s/secrets/%s/versions/%s',
			$this->project_id,
			$this->secret_id,
			$this->version
		);
	}

	public function getValue()
	{
		if (!$this->value) {
			$this->value = $this->resolveSecret();
		}

		return $this->value;
	}

	private function resolveSecret(): ?string
	{
		$secret = null;

		try {
			$client = new SecretManagerServiceClient();
			$response = $client->accessSecretVersion($this->getKey());
			$secret = $response->getPayload()->getData();
		} catch (\Google\ApiCore\ApiException $e) {
			Logger::error("failed secret access");
		}

		return $secret;	
	}
}