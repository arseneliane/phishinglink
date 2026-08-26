FROM node:22-alpine

WORKDIR /app
COPY index.html server.js ./

ENV PORT=10000
EXPOSE 10000

CMD ["node", "server.js"]
