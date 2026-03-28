import { defineConfig } from 'vitepress'

export default defineConfig({
  title: 'Items',
  description: 'Items: Lightweight PHP library for manipulating arrays and objects. Filter, sort, map, group arrays with static API or fluent interface. Fast array handler tool.',
  lang: 'en',
  base: '/Items/',

  head: [
    ['link', { rel: 'icon', href: 'favicon.ico', sizes: 'any' }],
    ['link', { rel: 'icon', type: 'image/svg+xml', href: '/logo.svg' }],
    ['link', { rel: 'apple-touch-icon', href: 'apple-touch-icon.png' }],
    ['link', { rel: 'manifest', href: '/Items/manifest.json' }],
    ['meta', { name: 'viewport', content: 'width=device-width, initial-scale=1.0' }],
    ['meta', { name: 'keywords', content: 'PHP array manipulation, array library, objects handler, array filtering, sort arrays, map arrays, PHP utility, array tool, nested data access' }],
    ['meta', { name: 'author', content: 'Alison' }],
    ['meta', { name: 'robots', content: 'index, follow' }],
    ['meta', { property: 'og:type', content: 'website' }],
    ['meta', { property: 'og:title', content: 'Items - PHP Array & Object Manipulation Library' }],
    ['meta', { property: 'og:description', content: 'Powerful PHP library for array manipulation. Filter, sort, map, group, and access nested data with ease. Three APIs: static immutable, in-place, and fluent.' }],
    ['meta', { property: 'og:image', content: 'https://github.com/alison4kk/items/raw/main/logo.svg' }],
    ['meta', { property: 'og:url', content: 'https://github.com/alison4kk/items' }],
    ['meta', { name: 'twitter:card', content: 'summary_large_image' }],
    ['meta', { name: 'twitter:title', content: 'Items - PHP Array & Object Manipulation Library' }],
    ['meta', { name: 'twitter:description', content: 'Lightweight PHP array handler tool. Manipulate arrays of items with filtering, sorting, grouping and more. Zero dependencies.' }],
    ['meta', { name: 'theme-color', content: '#3b82f6' }],
    ['link', { rel: 'canonical', href: 'https://github.com/alison4kk/items' }],
    ['link', { rel: 'alternate', hreflang: 'en', href: 'https://github.com/alison4kk/items' }],
    ['script', { type: 'application/ld+json' }, JSON.stringify({
      '@context': 'https://schema.org',
      '@type': 'SoftwareApplication',
      'name': 'Items - PHP Array Manipulation Library',
      'description': 'Lightweight PHP library for manipulating arrays and objects with multiple API styles',
      'url': 'https://github.com/alison4kk/items',
      'author': {
        '@type': 'Person',
        'name': 'Alison'
      },
      'applicationCategory': 'DeveloperApplication',
      'operatingSystem': 'Any',
      'programmingLanguage': 'PHP',
      'keywords': ['array', 'manipulation', 'filter', 'sort', 'map', 'PHP', 'library'],
      'offers': {
        '@type': 'Offer',
        'price': '0',
        'priceCurrency': 'USD'
      }
    })]
  ],

  themeConfig: {
    logo: '/logo.svg',
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
