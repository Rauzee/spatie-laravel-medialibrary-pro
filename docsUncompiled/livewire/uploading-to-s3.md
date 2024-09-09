---
title: Uploading to S3
weight: 4
---

Currently, Livewire does not support uploading multiple files to S3. That's why only the `attachment` component can be used to upload files to S3.

To get started with uploading files to `s3`, make sure to follow Livewire's instructions on [how to upload directly to S3](https://livewire.laravel.com/docs/uploads#storing-uploaded-files).

Next, make sure you have configured the media disk that uses the S3 driver.

With that configuration in place, the `livewire:media-library` component will now upload directly to S3.
