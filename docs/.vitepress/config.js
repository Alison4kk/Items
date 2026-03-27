import { defineConfig } from 'vitepress'

export default defineConfig({
  title: 'Items',
  description: 'Hybrid array/object manipulation library for PHP with static and fluent APIs',
  lang: 'en',
  base: '/Items/',

  themeConfig: {
    logo: '/logo.png',

    nav: [
      { text: 'Home', link: '/' },
      { text: 'Documentation', link: '/guide/' },
      { text: 'GitHub', link: 'https://github.com/alison4kk/items' }
    ],

    sidebar: {
      '/': [
        {
          text: 'Getting Started',
          collapsed: false,
          items: [
            { text: 'Introduction', link: '/guide/' },
            { text: 'Installation', link: '/guide/installation' },
            { text: 'Quick Start', link: '/guide/quickstart' },
            { text: 'Concepts', link: '/guide/concepts' }
          ]
        },
        {
          text: 'API Reference',
          collapsed: false,
          items: [
            { text: 'Filter', link: '/api/filter' },
            { text: 'Sort', link: '/api/sort' },
            { text: 'Map', link: '/api/map' },
            { text: 'Unique', link: '/api/unique' },
            { text: 'Group', link: '/api/group' },
            { text: 'Index', link: '/api/index' },
            { text: 'Find', link: '/api/find' },
            { text: 'Check', link: '/api/check' },
            { text: 'Aggregate', link: '/api/aggregate' },
            { text: 'Transform', link: '/api/transform' },
            { text: 'Path', link: '/api/path' }
          ]
        }
      ]
    },

    socialLinks: [
      { icon: 'github', link: 'https://github.com/alison4kk/items' }
    ],

    footer: {
      message: 'MIT License',
      copyright: 'Copyright © 2025-present Alison'
    }
  }
})
