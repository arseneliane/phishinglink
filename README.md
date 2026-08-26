# Geolocation Permission Demo

A transparent browser geolocation self-test. Coordinates are displayed locally and are never submitted to a backend.

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
- Coordinates are not transmitted, logged, or saved in browser storage.
