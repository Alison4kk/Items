# Items Documentation

Official documentation for the **Items** library - Hybrid PHP library for array and object manipulation.

## Local Development

### Prerequisites
- Node.js 16+ recommended
- npm or yarn

### Installation

```bash
npm install
```

### Run in development

```bash
npm run docs:dev
```

Documentation will be available at `http://localhost:5173`

### Build for production

```bash
npm run docs:build
```

Static files will be generated in `.vitepress/dist/`

### Preview build

```bash
npm run docs:preview
```

## Structure

```
docs/
├── index.md                 # Home page
├── guide/                   # "Getting Started" Section
│   ├── index.md            # Introduction
│   ├── installation.md      # Installation
│   ├── quickstart.md        # Quick Start
│   └── concepts.md          # Main Concepts
├── api/                     # "API Reference" Section
│   ├── filter.md
│   ├── sort.md
│   ├── map.md
│   ├── unique.md
│   ├── group.md
│   ├── index.md
│   ├── find.md
│   ├── check.md
│   ├── aggregate.md
│   ├── transform.md
│   └── path.md
├── .vitepress/
│   ├── config.js            # VitePress configuration
│   └── dist/                # Static build (gitignore)
├── package.json
└── README.md               # This file
```

## Editing Documentation

Documentation is written in Markdown. Each `.md` file generates a page.

### Add new page

1. Create a `.md` file in the appropriate directory (`/guide` or `/api`)
2. Add the link to the `sidebar` in `.vitepress/config.js`
3. The new file will be included automatically in the next build

### Internal links

Use relative or absolute paths:

```markdown
[Link](/guide/installation)
[Link](../guide/installation)
```

## Configuration

See `.vitepress/config.js` for:
- Site title and description
- Navigation (navbar)
- Sidebar with page structure
- Social links
- Footer

## Deploy

Documentation is ready for deployment on any static host:

- Vercel
- Netlify
- GitHub Pages
- Firebase Hosting
- etc.

Just publish the generated `.vitepress/dist/` directory.

## References

- [VitePress Docs](https://vitepress.dev/)
- [Markdown Guide](https://www.markdownguide.org/)
- [Items Library](https://github.com/alison4kk/items)
