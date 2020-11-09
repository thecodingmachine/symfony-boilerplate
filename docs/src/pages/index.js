import React from 'react';
import clsx from 'clsx';
import Layout from '@theme/Layout';
import Link from '@docusaurus/Link';
import useDocusaurusContext from '@docusaurus/useDocusaurusContext';
import useBaseUrl from '@docusaurus/useBaseUrl';
import styles from './styles.module.css';

const features = [
  {
    title: 'Development Environment',
    imageUrl: 'img/docker.svg',
    description: (
      <>
          Static analysis tools for your source code and a Docker Compose stack
          that will help you building a solid infrastructure in production.
      </>
    ),
  },
  {
    title: 'GraphQL API',
    imageUrl: 'img/graphqlite.svg',
    description: (
      <>
          Thanks to <a href="https://graphqlite.thecodingmachine.io/">GraphQLite</a>,
          you can build without hassle a GraphQL API around your PHP use cases.
      </>
    ),
  },
  {
    title: 'Nuxt.js',
    imageUrl: 'img/nuxtjs.svg',
    description: (
      <>
          Vue.js with Server-side Rendering (SSR)! Also, no more struggle with forms validation and access control,
          as everything is done on the API.
      </>
    ),
  },
];

function Feature({imageUrl, title, description}) {
  const imgUrl = useBaseUrl(imageUrl);
  return (
    <div className={clsx('col col--4', styles.feature)}>
      {imgUrl && (
        <div className="text--center">
          <img className={styles.featureImage} src={imgUrl} alt={title} />
        </div>
      )}
      <h3>{title}</h3>
      <p>{description}</p>
    </div>
  );
}

function Home() {
  const context = useDocusaurusContext();
  const {siteConfig = {}} = context;
  return (
    <Layout
      title={`${siteConfig.title}`}
      description="A Symfony boilerplate with GraphQL and Nuxt.js <head />">
      <header className={clsx('hero hero--primary', styles.heroBanner)}>
        <div className="container">
          <h1 className="hero__title">{siteConfig.title}</h1>
          <p className="hero__subtitle">{siteConfig.tagline}</p>
          <div className={styles.buttons}>
            <Link
              className={clsx(
                'button button--outline button--secondary button--lg',
                styles.getStarted,
              )}
              to={useBaseUrl('docs/')}>
              Get Started
            </Link>
          </div>
        </div>
      </header>
      <main>
        {features && features.length > 0 && (
          <section className={styles.features}>
            <div className="container">
              <div className="row">
                {features.map((props, idx) => (
                  <Feature key={idx} {...props} />
                ))}
              </div>
            </div>
          </section>
        )}
      </main>
    </Layout>
  );
}

export default Home;
