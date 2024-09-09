---
title: Usage
weight: 2
---

The attachment and collection components use Livewire, but no Livewire knowledge is needed to use them. You can use them in your Blade views without any Livewire-specific code.

## Handling a single upload

For single file uploads you can use the `attachment` component. This can be included inside your blade file like this:

```html
<form method="post">
    <x-media-library-attachment name="media" rules="mimes:png,jpeg,pdf"/>
    
    <button>Submit</button>
</form
```

## Handling multiple uploads

To handle multiple uploads you can just add the "multiple" property to your component:

```html
<x-media-library-attachment multiple name="media" rules="mimes:png,jpeg,pdf" />
```

Your controller method should look the same as for single file uploads.

## Managing collections

You can manage the entire contents of a media library collection with `livewire:media-library` component. This component is especially useful in admin sections.

```php
<form method="post">
    <x-media-library-collection
        name="images"
        :model="$formSubmission"
        collection="images"
        max-items="3"
        rules="mimes:png,jpeg"
    />
</form>
```

!(_partials/processing-uploads-on-the-server.md)
