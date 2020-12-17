---
title: CI/CD
slug: /deployments/cicd
---

## GitLab CI

```yaml title=".gitlab-ci.yml"
stages:
  - tests
  - build_push_docker_images

# ------------------------------------------
# TESTS
# ------------------------------------------

api_tests:
  stage: tests
  image: thecodingmachine/php:7.4-v3-cli
  services:
    - name: mysql:8.0
      command: ["--default-authentication-plugin=mysql_native_password"]
  variables:
    # Docker PHP image.
    # ---------------------
    APACHE_DOCUMENT_ROOT: "public/"
    PHP_EXTENSION_XDEBUG: "1"
    PHP_EXTENSION_REDIS: "1"
    PHP_EXTENSION_INTL: "1"
    PHP_EXTENSION_GD: "1"
    PHP_INI_MEMORY_LIMIT: "1G"
    # Docker MySQL image.
    # Make sure the values are matching
    # the corresponding values from SYMFONY_ENV_CONTENT.
    # ---------------------
    MYSQL_ROOT_PASSWORD: "foo"
    MYSQL_DATABASE: "foo"
    MYSQL_USER: "foo"
    MYSQL_PASSWORD: "foo"
    # Symfony.
    # ---------------------
    SYMFONY_ENV_CONTENT: "$SYMFONY_ENV_CONTENT_TESTS" # .env file content for tests (from GitLab CI/CD variables).
  before_script:
    - cd src/api
    - echo "$SYMFONY_ENV_CONTENT" > .env
  script:
    - composer install
    - composer dump-env test
    # Static analysis.
    - composer cscheck
    - composer phpstan
    - composer deptrac
    - composer yaml-lint
    # Integration tests.
    - composer pest

webapp_tests:
  stage: tests
  image: thecodingmachine/nodejs:12
  before_script:
    - cd src/webapp
  script:
    - yarn
    # Static analysis.
    - yarn lint

# ------------------------------------------
# BUILD PUSH DOCKER IMAGES
# ------------------------------------------

.api_build_push_docker_image:
  stage: build_push_docker_images
  image: docker:git
  services:
    - docker:dind
  variables:
    DOCKER_DRIVER: "overlay2"
    SYMFONY_ENV_CONTENT: "foo"
    ENV_NAME: "foo"
  before_script:
    - cd src/api
  script:
    - echo "$SYMFONY_ENV_CONTENT" > .env
    - docker login -u gitlab-ci-token -p "$CI_BUILD_TOKEN" registry.example.com
    - docker build -t "registry.example.com/group/project/api-$ENV_NAME:$CI_COMMIT_REF_SLUG" .
    - docker push "registry.example.com/group/project/api-$ENV_NAME:$CI_COMMIT_REF_SLUG"
  only:
    - tags

api_build_push_docker_image_testing:
  extends: .api_build_push_docker_image
  variables:
    SYMFONY_ENV_CONTENT: "$SYMFONY_ENV_CONTENT_TESTING" # .env file content for testing (from GitLab CI/CD variables).
    ENV_NAME: "testing"

api_build_push_docker_image_staging:
  extends: .api_build_push_docker_image
  variables:
    SYMFONY_ENV_CONTENT: "$SYMFONY_ENV_CONTENT_STAGING" # .env file content for staging (from GitLab CI/CD variables).
    ENV_NAME: "staging"

api_build_push_docker_image_prod:
  extends: .api_build_push_docker_image
  variables:
    SYMFONY_ENV_CONTENT: "$SYMFONY_ENV_CONTENT_PROD" # .env file content for prod (from GitLab CI/CD variables).
    ENV_NAME: "prod"

.webapp_build_push_docker_image:
  stage: build_push_docker_images
  image: docker:git
  services:
    - docker:dind
  variables:
    DOCKER_DRIVER: "overlay2"
    NUXTJS_ENV_CONTENT: "foo"
    ENV_NAME: "foo"
  before_script:
    - cd src/webapp
  script:
    - echo "$NUXTJS_ENV_CONTENT" > .env
    - docker login -u gitlab-ci-token -p "$CI_BUILD_TOKEN" registry.example.com
    - docker build -t "registry.example.com/group/project/webapp-$ENV_NAME:$CI_COMMIT_REF_SLUG" .
    - docker push "registry.example.com/group/project/webapp-$ENV_NAME:$CI_COMMIT_REF_SLUG"
  only:
    - tags

webapp_build_push_docker_image_testing:
  extends: .webapp_build_push_docker_image
  variables:
    NUXTJS_ENV_CONTENT: "$NUXTJS_ENV_CONTENT_TESTING" # .env file content for testing (from GitLab CI/CD variables).
    ENV_NAME: "testing"

webapp_build_push_docker_image_staging:
  extends: .webapp_build_push_docker_image
  variables:
    NUXTJS_ENV_CONTENT: "$NUXTJS_ENV_CONTENT_STAGING" # .env file content for staging (from GitLab CI/CD variables).
    ENV_NAME: "staging"

webapp_build_push_docker_image_prod:
  extends: .webapp_build_push_docker_image
  variables:
    NUXTJS_ENV_CONTENT: "$NUXTJS_ENV_CONTENT_PROD" # .env file content for prod (from GitLab CI/CD variables).
    ENV_NAME: "prod"
```
