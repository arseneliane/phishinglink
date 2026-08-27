# Geolocation Permission Demo

A transparent browser geolocation self-test. Coordinates are displayed locally first and are submitted to the service only after a separate, explicit consent checkbox and button press.

## Run locally with Docker

```sh
docker build -t geolocation-demo .
docker run --rm -p 8080:80 geolocation-demo
```

Open <http://localhost:8080>. Geolocation requires a secure context; browsers treat localhost as secure, and a hosted deployment should use HTTPS.

## Deploy on Render

Create a new Web Service from this repository and select the Docker runtime. The container listens on port 80. Render supplies HTTPS for the public service URL.

## Privacy behavior

- The browser prompt appears only after the visitor presses the location button.
- No login or password fields are presented.
- Reading coordinates does not transmit them.
- A separate checkbox explains collection and a separate button submits them.
- Consented entries are written to Render service logs as `CONSENTED_LOCATION_SUBMISSION` JSON records.

## Retrieve consented submissions

Open the service in the Render dashboard, choose **Logs**, and search for `CONSENTED_LOCATION_SUBMISSION`. Render controls log retention; this demo does not maintain a separate database.

The PHP receiver also appends human-readable entries to `/var/lib/location-demo/logs.txt`, outside the public web directory. That file is ephemeral on the current Render service and is not a permanent archive or a public download. The image copies only `index.html` and `capture.php`; the legacy Node server is no longer used. User agents are not collected.
