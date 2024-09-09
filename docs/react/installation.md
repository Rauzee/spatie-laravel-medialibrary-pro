---
title: Installation
weight: 1
---

## Getting a license

If you haven't already, you must buy a license on [the Media Library Pro product page](https://spatie.be/products/media-library-pro) at spatie.be
    
## Installing the base package

If you haven't installed the base Media Library package, make sure to do so by running:

```bash
composer require "spatie/laravel-medialibrary:^11.0.0"
```

## Requiring Media Library Pro

After you've purchased a license, add the `satis.spatie.be` repository in your `composer.json`.

```php
"repositories": [
    {
        "type": "composer",
        "url": "https://satis.spatie.be"
    }
],
```

Next, you need to create a file called `auth.json` and place it either next to the `composer.json` file in your project, or in the Composer home directory. You can determine the Composer home directory on \*nix machines by using this command.

```bash
composer config --list --global | grep home
```

This is the content you should put in `auth.json`:

```php
{
    "http-basic": {
        "satis.spatie.be": {
            "username": "<YOUR-SPATIE.BE-ACCOUNT-EMAIL-ADDRESS-HERE>",
            "password": "<YOUR-MEDIA-LIBRARY-PRO-LICENSE-KEY-HERE>"
        }
    }
}
```

To be sure you can reach `satis.spatie.be`,  clean your autoloaders before using this command:

```bash
composer dump-autoload
```

To validate if Composer can read your auth.json you can run this command:

```bash
composer config --list --global | grep satis.spatie.be
```

If you are using [Laravel Forge](https://forge.laravel.com), you don't need to create the `auth.json` file manually. Instead, you can set the credentials on the Composer Package Authentication screen of your server. Fill out the fields with these values:

- Repository URL: `satis.spatie.be`
- Username: Fill this field with your spatie.be account email address
- Password: Fill this field with your Media Library Pro license key

![screenshot](/docs/laravel-medialibrary-pro/v6/images/forge.png)

With the configuration above in place, you'll be able to install the Media Library Pro into your project using this command:

```bash
composer require "spatie/laravel-medialibrary-pro:^6.0.0"
```

## Prepare the database

Media Library Pro tracks all temporary uploads in a table called `temporary_uploads`.

To create the table you need to publish and run the migration:

```bash
php artisan vendor:publish --provider="Spatie\MediaLibraryPro\MediaLibraryProServiceProvider" --tag="media-library-pro-migrations"
php artisan migrate
```

## Automatically removing temporary uploads

If you are using Media Library Pro, you must schedule this artisan command in `app/Console/Kernel` to automatically delete temporary uploads

### Laravel 11 or higher
```php
// in bootstrap/app.php

->withSchedule(function (Schedule $schedule) {
    $schedule->command('media-library:delete-old-temporary-uploads')->daily();
})
```

### Laravel 10 or older
```php
// in app/Console/Kernel.php

protected function schedule(Schedule $schedule)
{
    $schedule->command('media-library:delete-old-temporary-uploads')->daily();
}
```

## Add the route macro

To accept temporary uploads via React and Vue, you must add this macro to your routes file.
You do not need to register this endpoint when using the Blade/Livewire components.

```php
// Probably routes/web.php

Route::mediaLibrary();
```

This macro will add the routes to controllers that accept file uploads for all components.

## Front-end setup

The easiest way to add the CSS and Javascript needed for the Media Library Pro components is to use the Blade directives `@mediaLibraryStyles` and `@mediaLibraryScripts`.

You should add the `@mediaLibraryStyles` directive before the closing `</head>` tag in your layout file.

If you want to include the CSS in a different way, you can find more information here: [Vite](/docs/laravel-medialibrary-pro/v6/styling/importing-css).

You should add the `@mediaLibraryScripts` directive before the closing `</body>` tag in your layout file.


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
