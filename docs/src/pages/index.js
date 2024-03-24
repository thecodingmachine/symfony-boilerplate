import clsx from 'clsx';
import Link from '@docusaurus/Link';
import useDocusaurusContext from '@docusaurus/useDocusaurusContext';
import Layout from '@theme/Layout';
import HomepageFeatures from '@site/src/components/HomepageFeatures';

import Heading from '@theme/Heading';
import styles from './index.module.css';


function HomepageHeader() {
    const {siteConfig} = useDocusaurusContext();
    return (<header className={clsx('hero hero--primary', styles.heroBanner)}>
            <div className="container">
                <img src="/img/logo_heroe.svg" height="200px"/>
                <Heading as="h1" className="hero__title text-dark">
                    {siteConfig.title}
                </Heading>
                <p className="hero__subtitle">{siteConfig.tagline}</p>
                <div className={styles.buttons}>
                    <Link
                        className="button button--secondary button--lg"
                        to="/docs/intro">
                        Getting started - a 5min guide of essentials ⏱️
                    </Link>
                </div>
            </div>
        </header>);
}

export default function Home() {
    const {siteConfig} = useDocusaurusContext();
    return (<Layout
            title={siteConfig.title}
            description="Description will go into a meta tag in <head />">
            <div className="p-1 text-center light-banner">
                You are currently browsing the v2 documentation, which is still in BETA version. If you are looking for
                the stable V1, it's <Link to="https://thecodingmachine.github.io/symfony-boilerplate/">here</Link>
            </div>
            <HomepageHeader/>
            <main>
                <HomepageFeatures/>
            </main>
        </Layout>);
}
