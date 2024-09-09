### Enabling Vapor support

If use React or Vue components to handle uploads you must set the `enable_vapor_uploads` key in the `media-library` config file to `true`. When enabling this option, a route will be registered that will enable
the Media Library Pro Vue and React components to move uploaded files in an S3 bucket to their right place.

With the config option enabled, the `Route::mediaLibrary();` will register a route at `/media-library-pro/post-s3`
instead of `/media-library-pro/uploads`.
