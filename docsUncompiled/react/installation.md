---
title: Installation
weight: 1
---

!(_partials/installation.md)

### Setup Vite
If you are using Vite, your `vite.config.js` look something like this:

```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    resolve: {
        alias: {
            'media-library-pro-react-attachment': '/vendor/spatie/laravel-medialibrary-pro/resources/js/media-library-pro-react-attachment',
            'media-library-pro-react-collection': '/vendor/spatie/laravel-medialibrary-pro/resources/js/media-library-pro-react-collection',
        }
    },
    plugins: [
        laravel([
            'resources/js/app.js',
        ]),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});

```

If you're using TypeScript, you will also have have to add this to your tsconfig:

```json
// tsconfig.json

{
    "compilerOptions": {
        "paths": {
            "*": ["*", "vendor/spatie/laravel-medialibrary-pro/resources/js/*"]
        }
    }
}
```

## Importing the components

_If you're developing a project where you don't have access to composer, you can download the package through GitHub Packages: [installation steps](/docs/laravel-medialibrary-pro/v6/advanced/usage-in-a-frontend-repository).

The components are located in `vendor/spatie/laravel-medialibrary-pro/resources/js` when you install the package through Composer. This makes for very long import statements, which you can clean up by adding some configuration to your Webpack/Laravel Mix configuration.
