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

Add the XDebug function in vscode launch.json file so that the debug mapper is pointed to the app folder:
```sh 
{
    "name": "Listen for Xdebug",
    "type": "php",
    "request": "launch",
    "port": 9003,
    "pathMappings": {
        "/app": "${workspaceFolder}/app"
},
```

Standard user and login information that are hardcoded with the purpose of testing:
PHPAdmin
```sh
developer
secret123
```
```sh
Bob user
password123

James admin
password123
```
