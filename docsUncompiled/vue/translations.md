---
title: Translations
weight: 6
---

## Translations

If you would like to use the components in your own language, you can pass a `translations` prop to the component.

```html
<media-library-collection
    :translations="{
        fileTypeNotAllowed: 'You must upload a file of type',
        tooLarge: 'File too large, max',
        tooSmall: 'File too small, min',
        tryAgain: 'please try uploading this file again',
        somethingWentWrong: 'Something went wrong while uploading this file',
        selectOrDrag: 'Select or drag files',
        selectOrDragMax: 'Select or drag max {maxItems} {file}',
        file: { singular: 'file', plural: 'files' },
        anyImage: 'any image',
        anyVideo: 'any video',
        goBack: 'Go back',
        dropFile: 'Drop file to upload',
        dragHere: 'Drag file here',
        remove: 'Remove',
        download: 'Download',
    }"
/>
```

The values mentioned here are the defaults. Feel free to only pass in a couple of keys, as your object will be merged onto the default.

If you use the component in different parts of your app, you might want to set the translations globally.

```js
window.mediaLibraryTranslations = {
    somethingWentWrong: "whoops",
    remove: "delete",
};
```

If you use the [vue-i18n](https://vue-i18n.intlify.dev/) package from intlify, you can also pass the keys from a translation file like `lang/media-library.php` by using the [`$tm`-function](https://vue-i18n.intlify.dev/api/composition.html#tm-key).

```js
<MediaLibraryCollection
    :translations="$tm('media-library')"
/>
```
