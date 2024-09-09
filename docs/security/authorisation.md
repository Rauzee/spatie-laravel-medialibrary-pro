---
title: Authorisation
weight: 1
---

## Only allow authenticated users to upload files

If in your project, you only want authenticated users to upload files, you can put the macro in a group that applies authentication middleware.

```php
Route::middleware('auth')->group(function() {
    Route::mediaLibrary();
});
```

We highly encourage you to do this, if you only need authenticated users to upload files.
