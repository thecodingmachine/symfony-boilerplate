module.exports = {
  title: 'Symfony Boilerplate',
  tagline: 'A Symfony boilerplate with GraphQL and Nuxt.js',
  url: 'https://thecodingmachine.github.io',
  baseUrl: '/symfony-boilerplate/',
  onBrokenLinks: 'throw',
  favicon: 'img/favicon.ico',
  organizationName: 'thecodingmachine', // Usually your GitHub org/user name.
  projectName: 'symfony-boilerplate', // Usually your repo name.
  themeConfig: {
    googleAnalytics: {
      trackingID: 'UA-10196481-13',
      // Optional fields.
      anonymizeIP: true, // Should IPs be anonymized?
    },
    colorMode: {
      defaultMode: 'light',
      disableSwitch: true,
    },
    navbar: {
      title: 'Symfony Boilerplate',
      logo: {
        alt: 'Symfony Boilerplate Logo',
        src: 'img/logo.svg',
      },
      items: [
        {
          to: 'docs/',
          activeBasePath: 'docs',
          label: 'Docs',
          position: 'left',
        },
        {
          href: 'https://github.com/thecodingmachine/symfony-boilerplate',
          label: 'GitHub',
          position: 'right',
        },
      ],
    },
    footer: {
      style: 'dark',
      copyright: `Copyright © ${new Date().getFullYear()} TheCodingMachine, Inc. Built with Docusaurus.`,
    },
  },
  presets: [
    [
      '@docusaurus/preset-classic',
      {
        docs: {
          sidebarPath: require.resolve('./sidebars.js'),
          // Please change this to your repo.
          editUrl:
            'https://github.com/thecodingmachine/symfony-boilerplate/tree/master/docs',
        },
        theme: {
          customCss: require.resolve('./src/css/custom.css'),
        },
      },
    ],
  ],
};
