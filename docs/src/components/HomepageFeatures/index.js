import clsx from 'clsx';
import Heading from '@theme/Heading';
import styles from './styles.module.css';


function FeatureList() {
    return [
        {
            title: 'Deploy Ready',
            src: '/img/docker.svg',
            description: (
                <>
                    Static analysis tools for your source code and a Docker Compose stack that will help you building a solid infrastructure in production.
                </>
            ),
        },
        {
            title: 'Robust yet Modular stack',
            src: '/img/robust_modular.svg',
            description: (
                <>
                    This boilerplate relies on a very common a stable set of copmponents : Symfony 6, Nuxt 3 and REST. Adding a new tech in the stack, is easy, adapt it according to your needs !
                </>
            ),
        },
        {
            title: 'Just start implementing !',
            src: '/img/fast_code.png',
            description: (
                <>
                    This boilerplate's philosophy is to embed the common use cases that will be needed to 90% of Web Apps. You just start implementing features.

                </>
            ),
        },
    ];
}


function Feature({src, title, description}) {
  return (
    <div className={clsx('col col--4')}>
      <div className="text--center">
        <img className={styles.featureSvg} role="img" src={src} />
      </div>
      <div className="text--center padding-horiz--md">
        <Heading as="h3">{title}</Heading>
        <p>{description}</p>
      </div>
    </div>
  );
}

export default function HomepageFeatures() {
  return (
    <section className={styles.features}>
      <div className="container">
        <div className="row">
          {FeatureList().map((props, idx) => (
            <Feature key={idx} {...props} />
          ))}
        </div>
      </div>
    </section>
  );
}
