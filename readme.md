# ShipStation Note Parser

When orders are synced from Shopify to Shipstation,  
order notes and order attributes are merged into a single field.

This unseperates them.

Uses Shipstation's ORDER_NOTIFY webhook to process orders as they are imported.  
Removes order attributes from "Note from Buyer" field and puts them in "Custom Field 1"

**Before:**
```
Note From Buyer: Special Note 4 U<br \> internal-track-data: 123abc
```
**After:**
```
Note From Buyer: Special Note 4 U
...
Custom Field 1: internal-track-data: 123abc
```

## Tech
- Google Cloud Functions
- Google Secret Manager
- ShipStation Webhooks 
- ShipStation API

## Prereqs
- `gcloud` cli: https://cloud.google.com/sdk/gcloud

## Setup
1. Clone repo
2. Generate ShipStation API key
3. Create new Google Secret with API key (secret value format should be `key:secret`)
4. Rename `config.template.ini` -> `config.ini`
5. Update `config.ini` with project_id, secret_name, version
6. Update `config.ini` with ShipStation base URL
7. Deploy cloud function (replace PROJECT, REGION)
```
gcloud functions deploy shipstation-note-parser \
--gen2 \
--project PROJECT \
--region REGION \
--runtime php82 \
--trigger-http \
--entry-point run 
```
8. Get Cloud Function URL (either cloudfunctions.net or run.app)
9. Create ShipStation webhook (On New Orders) with Cloud Function URL.

## Testing
Run Functions Framework
https://cloud.google.com/functions/docs/running/function-frameworks

```
composer start
```

Call function 
(Use a service like https://webhook.site to capture webhook POST data to test with)

```
curl -X POST \
-H "Content-Type: application/json" \
-d '{"resource_url":"URL","resource_type":"ORDER_NOTIFY"}' \
http://localhost:8080
```



