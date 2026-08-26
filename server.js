const http = require('node:http');
const fs = require('node:fs');
const path = require('node:path');

const port = Number(process.env.PORT || 10000);
const index = fs.readFileSync(path.join(__dirname, 'index.html'));

function json(response, status, body) {
  response.writeHead(status, {
    'Content-Type': 'application/json; charset=utf-8',
    'Cache-Control': 'no-store',
    'X-Content-Type-Options': 'nosniff'
  });
  response.end(JSON.stringify(body));
}

const server = http.createServer((request, response) => {
  if (request.method === 'GET' && request.url === '/') {
    response.writeHead(200, {
      'Content-Type': 'text/html; charset=utf-8',
      'Cache-Control': 'no-store',
      'Content-Security-Policy': "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; connect-src 'self'; img-src 'self'; frame-ancestors 'none'",
      'Referrer-Policy': 'no-referrer',
      'X-Content-Type-Options': 'nosniff'
    });
    response.end(index);
    return;
  }

  if (request.method === 'GET' && request.url === '/health') {
    json(response, 200, { status: 'ok' });
    return;
  }

  if (request.method === 'POST' && request.url === '/api/location') {
    let body = '';
    request.on('data', chunk => {
      body += chunk;
      if (body.length > 4096) request.destroy();
    });
    request.on('end', () => {
      try {
        const data = JSON.parse(body);
        const latitude = Number(data.latitude);
        const longitude = Number(data.longitude);
        const accuracy = Number(data.accuracy);

        if (
          data.consent !== true ||
          !Number.isFinite(latitude) || latitude < -90 || latitude > 90 ||
          !Number.isFinite(longitude) || longitude < -180 || longitude > 180 ||
          !Number.isFinite(accuracy) || accuracy < 0
        ) {
          json(response, 400, { status: 'invalid' });
          return;
        }

        const entry = {
          event: 'CONSENTED_LOCATION_SUBMISSION',
          receivedAt: new Date().toISOString(),
          latitude: Number(latitude.toFixed(5)),
          longitude: Number(longitude.toFixed(5)),
          accuracy: Math.round(accuracy),
          capturedAt: typeof data.capturedAt === 'string' ? data.capturedAt : null
        };
        console.log(JSON.stringify(entry));
        json(response, 201, { status: 'received' });
      } catch {
        json(response, 400, { status: 'invalid' });
      }
    });
    return;
  }

  json(response, 404, { status: 'not_found' });
});

server.listen(port, '0.0.0.0', () => {
  console.log(`Geolocation consent demo listening on port ${port}`);
});
