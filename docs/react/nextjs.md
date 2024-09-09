---
title: Next.js
weight: 5
---

Because the components need references `document` and `window`, Server Side Rendering won't work. This means you'll have to use [dynamic imports](https://nextjs.org/docs/advanced-features/dynamic-import) to get the UI components to work.

```js
import dynamic from "next/dynamic";
const MediaLibraryCollection = dynamic(
    () => import("@spatie/media-library-pro-react-collection"),
    { ssr: false }
);
```
