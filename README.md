# Bloomreach API Mock

Be able to simulate and trace API requests that are supposed to go to the Bloomreach API in the local development environment.

Start server, with whatever port suits you

```
php -S 127.0.0.1:8585 server.php
```

Request
```
127.0.0.1:8585/api... 
```

```
# ingest request
PUT: /api/accounts/{accountId}/catalogs/{catalogName}/products
# status request
GET: /api/jobs/{jobId}
```

