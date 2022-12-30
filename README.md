# Boilerplate TheCodingMachine

# Setup the project

```
make init-dev
```

# Merge request

To check:

- if entities has changed, a new migration should be created

### Error

@see https://datatracker.ietf.org/doc/html/rfc7807 via  symfony/serializer-pack and https://symfony.com/doc/current/controller/error_pages.html

For loggin:  Monolog\Formatter\JsonFormatter (this is WIP)
## Database

### Update the database
The database access is configured directly via the environment variable "DATABASE_URL", In dev:

#### HOW TO

- In development

During development it is safe to assume:

```
composer run  console -- doctrine:schema:update  --force
```

working

- In production

To deploy new structures in production (IE mapping update):
1. Generate a migration

```
composer run  console  -- doctrine:migrations:diff
```

2. Apply the migration in production

```
composer run  console  --  doctrine:migrations:migrate
```

### Create an entity

- Entity

```
declare(strict_types=1);

namespace App\Modules\Dummy\Entity;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity()]
class Dummy implements JsonSerializable {

    #[ORM\Id()]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\CustomIdGenerator(class: \Ramsey\Uuid\Doctrine\UuidGenerator::class)]
    private $id;

    public function jsonSerialize(): array { 
        return [
            "id" => $this->id
        ];

    }

}
```

- Repository (here the name of the entity is event, and the domain name is event too)


```
<?php

declare(strict_types=1);

namespace App\Modules\Dummy\Repository;

use App\Modules\Dummy\Entity\Dummy as EntityDummy;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class Dummy
{
    private EntityManagerInterface $entityManager;
  /**
   * @var EntityRepository<EntityDummy>
   */
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(EntityDummy::class);
    }

    /**
     * @return iterable<EntityDummy>
     */
    public function findAll(): iterable
    {
        return $this->repository->findAll();
    }
}
```



# BASICS

## How the backend it has been created
```
docker run --rm  --volume=`pwd`/app:/usr/src/app/:rw thecodingmachine/php:8.0-v4-apache -- bash -c "cd '/usr/src/app/' && composer create-project symfony/skeleton backend"
```

## Install composer package inside the project
```
docker run --rm  --volume=`pwd`/app/backend:/usr/src/app/:rw thecodingmachine/php:8.0-v4-apache -- bash -c "cd '/usr/src/app/' && composer require mypackage"
```

## Run a command
```
docker run --rm  --volume=`pwd`/app/backend:/usr/src/app/:rw thecodingmachine/php:8.0-v4-apache -- bash -c "cd '/usr/src/app/' && php bin/console debug:config monolog"
```

## Must to read

https://symfony.com/doc/current/configuration.html#configuration-environments

## Update the database


connect to the container then run

```
php bin/console doctrine:sc:update --dump-sql
```