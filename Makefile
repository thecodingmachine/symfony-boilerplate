# .RECIPEPREFIX = "    "
# Change tabs to space in makefile

# Load .env variable (the prod is added also if there is any)
ifneq (,$(wildcard ./.env))
    include .env
    export
endif
ifneq (,$(wildcard ./.env.prod))
    include .env
    export
endif

# connect to the back container
.PHONY: bash
bash: ;\
    docker compose exec back bash;


# Launch migration
.PHONY: migrate
migrate: ;\
    docker compose exec back composer -- run  console  doctrine:migrations:migrate -n

# Force doctrine update from entities
.PHONY: db-dev-mig
db-dev-mig: ;\
    docker compose exec back composer -- run console doctrine\:schema\:update -f

# Launch generate migration (recommended) from database diff
# doctrine:schema:validate --skip-sync  is used to check the database mapping before generating the migration,
# skip-sync is used to skip database and mapping sync validation
db-mig-diff: ;\
    docker compose exec back composer -- run console  doctrine:schema:validate --skip-sync && \
    docker compose exec back composer -- run console make:migration

# Launch generate migration (not recommended)
.PHONY: migrate-diff
db-migrate-diff: ;\
    docker compose exec back composer -- run  console  doctrine:migrations:diff -n

# See logs of back
.PHONY: backlogs
backlogs: ;\
    docker-compose logs back -f
# Init dev env
init-dev: ;\
    cp -n docker-compose.override.yml.template docker-compose.override.yml; \
    cp -n .env.dist .env; \
    echo "Add ${BASE_DOMAIN} and ${API_DOMAIN} to your /etc/host";

#
# Theses are usefull when you use docker
#

# down docker compose
down: ;\
    docker compose down
# up docker compose
up: ;\
    DOCKER_BUILDKIT=1 docker compose up -d

# stronger down (remove volume / image / orphans)
.PHONY: fdown
fdown: ;\
   docker compose down -v --remove-orphans

# stronger up (recreate all container and rebuild the image)
fup: ;\
    DOCKER_BUILDKIT=1 docker compose up -d --force-recreate --build

# Soft Restart
.PHONY: restart
restart: down up

# Hard restart
.PHONY: frestart
frestart: fdown fup


#
# Theses are static analyses + tests
#

.PHONY: phpmd
phpmd: ;\
	docker compose exec back composer -- run phpmd

.PHONY: cs-fix
cs-fix: ;\
	docker compose exec back composer -- run cs-fix


.PHONY: cs-check
cs-check: ;\
	docker compose exec back composer -- run cs-check

.PHONY: phpstan
phpstan: ;\
	docker compose exec back composer -- run phpstan

# Run all CI tools
.PHONY: ci
ci: cs-fix phpstan phpmd

.PHONY: dump
dump: ;\
    docker compose exec mysql mysqldump -u ${DATABASE_USERNAME} -p${DATABASE_PASSWORD} ${DATABASE_NAME} > apps/back/dump/dump.sql
