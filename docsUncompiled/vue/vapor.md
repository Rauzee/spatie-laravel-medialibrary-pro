---
title: Vapor support
weight: 6
---

If you are planning on deploying your application to AWS using [Laravel Vapor](https://vapor.laravel.com/), you will need to do some extra configuration to make sure files are uploaded properly to an S3 bucket.

First off, make sure you have [enabled Vapor support in Laravel](./processing-uploads-on-the-server#enabling-vapor-support).

You will also need to set the `vapor` prop in your components.

```html
<media-library-attachment name="media" vapor />
```

If you edited Vapor's signed storage URL in Laravel, you will need to pass the new endpoint to your components in `vapor-signed-storage-url`. It will use `/vapor/signed-storage-url` by default.

```html
<media-library-attachment
    name="media"
    vapor
    vapor-signed-storage-url="/vapor/signed-storage-url"
/>
```
