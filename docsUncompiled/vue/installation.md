---
title: Installation
weight: 1
---

!(_partials/installation.md)

## Setup Vite
If you are using Vite, your `vite.config.js` look something like this:

```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    resolve: {
        alias: {
            'media-library-pro-vue3-attachment': '/vendor/spatie/laravel-medialibrary-pro/resources/js/media-library-pro-vue3-attachment',
            'media-library-pro-vue3-collection': '/vendor/spatie/laravel-medialibrary-pro/resources/js/media-library-pro-vue3-collection',
            'vue': 'vue/dist/vue.esm-bundler.js',
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

## Importing the components

By default the components aren't available through npm, but are located in `vendor/spatie/laravel-medialibrary-pro/resources/js`..

_If you're developing a project where you don't have access to composer, you can download the package through GitHub Packages: [installation steps](/docs/laravel-medialibrary-pro/v6/advanced/usage-in-a-frontend-repository)_

To use a component in your Blade templates, import the components you plan to use in your `app.js` file, and add them to your main Vue app's `components` object.

```js
import { createApp } from "vue";
import { MediaLibraryAttachment } from "media-library-pro-vue3-attachment";
import { MediaLibraryCollection } from "media-library-pro-vue3-collection";

createApp({
    components: {
        MediaLibraryAttachment,
        MediaLibraryCollection,
    },
}).mount("#app");
```

You can now use them in any `.blade.php` file in your application.

```html
<!-- posts/edit.blade.php -->

<div id="app">
    <form>
        <media-library-attachment name="cover" />
        <media-library-collection name="images" />
        <button>Submit</button>
    </form>
</div>
```

You may also choose to import the components on the fly in a `.vue` file.

```html
<!-- EditPost.vue -->
<template>
    <form>
        <media-library-attachment name="cover" />
        <media-library-collection name="images" />
        <button>Submit</button>
    </form>
</template>

<script>
    import { MediaLibraryAttachment } from "media-library-pro-vue3-attachment";
    import { MediaLibraryCollection } from "media-library-pro-vue3-collection";

    export default {
        components: {
            MediaLibraryAttachment,
            MediaLibraryCollection,
        },
    };
</script>
```
