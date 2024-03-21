# WebDevelopment2

## Usage

In a terminal, run:
```sh
docker-compose up
```

NGINX will now serve files in the app/public folder. Visit localhost in your browser to check.
PHPMyAdmin is accessible on localhost:8080

If you want to stop the containers, press Ctrl+C. 
Or run:
```sh
docker-compose down
```

rebuild 
```sh
docker-compose up --build --force-recreate --no-deps 
```