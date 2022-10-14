# Audioteria

## Getting Started

## Note

At least Node.js version 14 is required for the project. You may use [Fast Node Manager](https://github.com/Schniz/fnm) or [Node Version Manager](https://github.com/nvm-sh/nvm) to configure different Node.js versions as needed.

First, install the dependencies:

```bash
npm i
```

Second, create a .env file and add this in it:

```bash
TAILWIND_IN=public/styles/sass/main.css
TAILWIND_OUT=public/styles/styles.css
```

Third, run the development server:

```bash
npm run dev
```

## Code Style Guide
- Apply tailwind class in css/scss.
- Don't use inline styles.
- Use snake_case convection for file names. Ex. ``terms_and_conditions.js``
