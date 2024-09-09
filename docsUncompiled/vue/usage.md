---
title: Usage
weight: 2
---

## Your first components

The most basic components have a `name` prop. This name will be used to identify the media when it's uploaded to the server.

```html
<!-- MyImageUploader.vue -->

<template>
    <form>
        <media-library-attachment name="avatar" />
        <media-library-collection name="downloads" />
        <button>Submit</button>
    </form>
</template>

<script>
    import { MediaLibraryAttachment } from "media-library-pro-vue3-attachment";
    import { MediaLibraryCollection } from "media-library-pro-vue3-collection";

    export default {
        components: {
            MediaLibraryAttachment,
            MediaLibraryCollection,
        },
    };
</script>
```

### Passing an initial value

If your form modifies an existing set of media, you may pass it through in the `initial-value` prop.

You can retrieve your initial values in Laravel using `$yourModel->getMedia($collectionName)`. This will also take care of any `old` values after an invalid form submit. You can also use this straight in your blade file:

```html
<form>
    <media-library-attachment
        name="avatar"
        :initial-value="$post->getMedia('avatar')"
    />

    <media-library-collection
        name="downloads"
        :initial-value="$post->getMedia('downloads')"
    />

    <button>Submit</button>
</form>
```

Under the hood, these components create hidden `<input />` fields to keep track of the form values on submit. If you would like to submit your values asynchronously, refer to [the `Asynchronously submit data` section](#asynchronously-submit-data).

### Setting validation rules

You'll probably want to validate what gets uploaded. Use the `validation-rules` prop, and don't forget to pass Laravel's validation errors too. The validation errors returned from the server will find errors under the key used in your `name` prop.

```html
<form>
    <media-library-attachment
        name="avatar"
        :initial-value="$post->getMedia('avatar')"
        :validation-rules="{ accept: ['image/png', 'image/jpeg'], maxSizeInKB: 5000 }"
        :validation-errors="$errors"
    />

    <media-library-collection
        name="downloads"
        :initial-value="$post->getMedia('downloads')"
        :validation-rules="{ accept: ['image/png', 'image/jpeg'], maxSizeInKB: 5000 }"
        :validation-errors="$errors"
    />

    <button>Submit</button>
</form>
```

You can also set the maximum amount of images that users can be uploaded using the `max-items` prop. Don't forget to set the `multiple` prop for the attachment component.

```html
<form>
    <media-library-attachment name="files" :max-items="2" multiple />

    <media-library-collection name="downloads" :max-items="5" />

    <button>Submit</button>
</form>
```

See the [Validation rules section](#validation-rules) for a complete list of all possible validation rules.

### Checking the upload state

The components keep track of whether they're ready to be submitted, you can use this to disable a submit button while a file is still uploading or when there are frontend validation errors. This value can be tracked by listening to a `is-ready-to-submit-change` event on the components. If you submit a form while a file is uploading, Laravel will return a HTTP 500 error with an `invalid uuid` message.

```html
<template>
    <form>
        <media-library-attachment
            name="avatar"
            @is-ready-to-submit-change="isReadyToSubmit = $event"
        />

        <button :disabled="!isReadyToSubmit">Submit</button>
    </form>
</template>

<script>
    import { MediaLibraryAttachment } from "media-library-pro-vue3-attachment";

    export default {
        components: { MediaLibraryAttachment },

        data() {
            return {
                isReadyToSubmit: true,
            };
        },
    };
</script>
```

### Using custom properties

The Media Library supports [custom properties](/docs/laravel-medialibrary/v11/advanced-usage/using-custom-properties) to be saved on a media item. The values for these can be chosen by your users. By default, the `MediaLibraryAttachment` component doesn't show any input fields, and the `MediaLibraryCollection` component only shows a `name` field, with the option to add more fields.

Use the `fields` scoped slot to add some fields:

```html
<media-library-collection name="images" :initial-value="{{ $images }}">
    <template
        #fields="{
            getCustomPropertyInputProps,
            getCustomPropertyInputListeners,
            getCustomPropertyInputErrors,
            getNameInputProps,
            getNameInputListeners,
            getNameInputErrors,
        }"
    >
        <div class="media-library-properties">
            <div class="media-library-field">
                <label class="media-library-label">Name</label>
                <input
                    class="media-library-input"
                    v-bind="getNameInputProps()"
                    v-on="getNameInputListeners()"
                />
                <p
                    v-for="error in getNameInputErrors()"
                    :key="error"
                    class="media-library-text-error"
                >
                    @{{ error }}
                </p>
            </div>

            <div class="media-library-field">
                <label class="media-library-label">Extra field</label>
                <input
                    class="media-library-input"
                    v-bind="getCustomPropertyInputProps('extra_field')"
                    v-on="getCustomPropertyInputListeners('extra_field')"
                />
                <p
                    v-for="error in getCustomPropertyInputErrors('extra_field')"
                    :key="error"
                    class="media-library-text-error"
                >
                    @{{ error }}
                </p>
            </div>
        </div>
    </template>
</media-library-collection>
```

When you add an image to your collection, it will look like this.

![Screenshot of custom property](/docs/laravel-medialibrary-pro/v6/images/extra.png)

### Customizing the file properties

When uploading a file, some properties appear by default: its extension, filesize and a remove or download button (respectively for the attachment or collection component).

You can customize what is displayed here by using the `properties` scoped slot:

```html
<media-library-attachment
    name="images"
    :initial-value="{{ $images }}"
>
    <template #properties="{ object }">
        <div class="media-library-property">
            {{ object.attributes.name }}
        </div>
    </template>
</media-library-collection>
```

### Asynchronously submit data

If you don't want to use traditional form submits to send your data to the backend, you will have to keep track of the current value of the component using the `onChange` handler. The syntax is the same for all UI components:

```html
<template>
    <div>
        <media-library-attachment
            name="avatar"
            :initial-value="media"
            :validation-errors="validationErrors"
            @change="onChange"
        />

        <media-library-collection
            name="media"
            :initial-value="media"
            :validation-errors="validationErrors"
            @change="onChange"
        />

        <button @click="submitForm">Submit</button>
    </div>
</template>

<script>
    import Axios from "axios";

    export default {
        props: { values },

        data() {
            return {
                validationErrors: {},
                media: this.values.media,
            };
        },

        methods: {
            onChange(media) {
                this.media = media;
            },

            submitForm() {
                Axios.post("endpoint", { media: this.media }).catch(
                    (error) => (this.validationErrors = error.data.errors)
                );
            },
        },
    };
</script>
```

## Validation rules

There are a couple of different ways to validate files on the frontend. These props are available to you: `validationRules`, `maxItems` and `beforeUpload`.

**validationRules**

In the `validationRules` object, we've got the `accept` property, which expects an array of MIME types as strings. Leave it empty to accept all types of files, set its value to `['image/*']` to accept any type of image, or choose your own set of rules using [MIME types](https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Common_types). Remember, the only valid MIME type of a JPEG/JPG is `image/jpeg`!

The `minSizeInKB` and `maxSizeInKB` properties set the minimum and maximum size of any individual file.

```html
<media-library-attachment
    name="avatar"
    :validation-rules="{
        accept: ['image/jpeg', 'image/gif', 'application/pdf'],
        minSizeInKB: 512,
        maxSizeInKB: 512,
    }"
/>
```

**maxItems**

Set the maximum amount of items in the collection/attachment component at any time.

```html
<media-library-attachment name="avatar" :max-items="3" />
```

**beforeUpload**

Pass a method to `before-upload` that accepts a [file](https://developer.mozilla.org/en-US/docs/Web/API/File) parameter. Return any value (or resolve a Promise with any value) from this function to upload the file. Throw an Error in this function to cause the file not to be uploaded, and display your error message.

```html
<template>
    <media-library-attachment
        name="avatar"
        :before-upload="checkFileValidity"
    />
</template>

<script>
    export default {
        …

        methods: {
            checkFileValidity(file) {
                return new Promise((resolve) => {
                    if (file.size < 1000) {
                        return resolve();
                    }

                    throw new Error("The uploaded file is too big");
                });
            }
        },
    }
</script>
```

!(_partials/processing-uploads-on-the-server.md)
