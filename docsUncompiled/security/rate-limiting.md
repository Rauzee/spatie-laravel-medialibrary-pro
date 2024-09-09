---
title: Rate limiting
weight: 3
---

To protect you from users that upload too many files, the temporary uploads controllers are rate limited. By default, only 10 files can be upload per minute per ip iddress.

To customize rate limiting, add [a rate limiter](https://laravel.com/docs/master/rate-limiting#introduction) named `medialibrary-pro-uploads`. Typically, this would be done in a service provider.

Here's an example where's we'll allow 15 files:

```php
// in a service provider

use Illuminate\Support\Facades\RateLimiter;

RateLimiter::for('medialibrary-pro-uploads', function (Request $request) {
    return [
        Limit::perMinute(10)->by($request->ip()),
    ];
});
```
