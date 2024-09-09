---
title: Translations
weight: 6
---

## Translations

If you would like to use the components in your own language, you can pass a `translations` prop to the component.

```jsx
translations = {
    fileTypeNotAllowed: "You must upload a file of type",   
    tooLarge: "File too large, max",
    tooSmall: "File too small, min",
    tryAgain: "please try uploading this file again",
    somethingWentWrong: "Something went wrong while uploading this file",
    selectOrDrag: "Select or drag files",
    selectOrDragMax: "Select or drag max {maxItems} {file}",
    file: { singular: "file", plural: "files" },
    anyImage: "any image",
    anyVideo: "any video",
    goBack: "Go back",
    dropFile: "Drop file to upload",
    dragHere: "Drag file here",
    remove: "Remove",
    download: "Download",
};

return <MediaLibraryCollection translations={translations} />;
```

The values mentioned here are the defaults. Feel free to only pass in a couple of keys, as your object will be merged onto the default.

If you use the component in different parts of your app, you might want to set the translations globally.

```js
window.mediaLibraryTranslations = {
    somethingWentWrong: "whoops",
    remove: "delete",
};
```
