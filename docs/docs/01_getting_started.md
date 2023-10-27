// prepare dev env
```
cp docker-compose.override.yml.template docker-compose.override.yml
cp .env.dist .env
```

// start project
```
DOCKER_BUILDKIT=1 docker-compose up -d --build\n
```

// update /etc/hosts
```
127.0.0.1 boilerplatev2.localhost
```


