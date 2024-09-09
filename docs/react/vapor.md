---
title: Vapor support
weight: 6
---

If you are planning on deploying your application to AWS using [Laravel Vapor](https://vapor.laravel.com/), you will need to do some extra configuration to make sure files are uploaded properly to an S3 bucket.

!(partials/vapor.md)

## Configuring the component

You will also need to set the `vapor` prop in your components.

```jsx
<MediaLibraryAttachment name="media" vapor />
```

If you edited Vapor's signed storage URL in Laravel, you will need to pass the new endpoint to your components in `vaporSignedStorageUrl`. It will use `/vapor/signed-storage-url` by default.

```jsx
<MediaLibraryAttachment
    name="media"
    vapor
    vaporSignedStorageUrl="/vapor/signed-storage-url"
/>
```
