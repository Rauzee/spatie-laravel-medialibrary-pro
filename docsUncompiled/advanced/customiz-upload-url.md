---
title: Customize the upload URL
weight: 4
---

## Customizing the upload URL

You can customize the upload url by passing a base url to the macro.

```php
// Probably routes/web.php

Route::mediaLibrary('my-custom-url');
```

This will register a route at `/my-custom-url/uploads`
