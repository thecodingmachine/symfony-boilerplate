<template>
  <div>
    <b-row>
      <h1 class="display-2">
        {{ $t('pages.home.welcome') }}
        <small class="text-muted">Symfony Boilerplate</small>
      </h1>
    </b-row>
    <b-row class="justify-content-center">
      <p class="lead">
        {{ $t('pages.home.message') }}
      </p>
    </b-row>
    <b-row class="justify-content-center">
      <ul class="list-inline">
        <li class="list-inline-item">
          <b-button
            variant="primary"
            href="https://thecodingmachine.github.io/symfony-boilerplate/"
            target="_blank"
          >
            Documentation
          </b-button>
        </li>
        <li class="list-inline-item">
          <b-button
            variant="outline-primary"
            href="https://github.com/thecodingmachine/symfony-boilerplate"
            target="_blank"
            >GitHub</b-button
          >
        </li>
      </ul>
    </b-row>
  </div>
</template>
