---
title: Configure allowed mime types
weight: 2
---

For security purposes, only files that pass [Laravel's `mimes` validation](https://laravel.com/docs/master/validation#rule-mimetypes) with the extensions [mentioned in this class](https://github.com/spatie/laravel-medialibrary-pro/blob/ba6eedd5b2a7f743909b441c0b6fd111d1a73794/src/Support/DefaultAllowedExtensions.php#L5) are allowed by the temporary upload controllers.

If you want your components to accept other mimetypes, add a key `temporary_uploads_allowed_extensions` in the `media-library.php` config file.

```php
// in config/medialibrary.php

return [
   // ...
   
   'temporary_uploads_allowed_extensions' => [
        // your extensions
        ... \Spatie\MediaLibraryPro\Support\DefaultAllowedExtensions::all(), // add this if you want to allow the default ones too
   ],
],
```
