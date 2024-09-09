---
title: Usage
weight: 2
---

## Your first components

The most basic components have a `name` prop. This name will be used to identify the media when it's uploaded to the server.

```jsx
// MyImageUploader.jsx

import { MediaLibraryAttachment } from "media-library-pro-react-attachment";
import { MediaLibraryCollection } from "media-library-pro-react-collection";

export default function MyImageUploader() {
    return (
        <form>
            <MediaLibraryAttachment name="avatar" />
            <MediaLibraryCollection name="downloads" />

            <button>Submit</button>
        </form>
    );
}
```

### Passing an initial value

If your form modifies an existing set of media, you may pass it through in the `initialValue` prop.

You can retrieve your initial values in Laravel using `$yourModel->getMedia($collectionName);`, this will also take care of any `old` values after an invalid form submit.

```jsx
<form>
    <MediaLibraryAttachment name="avatar" initialValue={avatar} />
    <MediaLibraryCollection name="downloads" initialValue={downloads} />

    <button>Submit</button>
</form>
```

Under the hood, these components create hidden `<input />` fields to keep track of the form values on submit. If you would like to submit your values asynchronously, refer to the [Asynchronously submit data](#asynchronously-submit-data) section.

### Setting validation rules

You'll probably want to validate what gets uploaded. Use the `validationRules` prop, and don't forget to pass Laravel's validation errors too. The validation errors returned from the server will find errors under the key used in your `name` prop.

```jsx
<form>
    <MediaLibraryAttachment
        name="avatar"
        initialValue={avatar}
        validationRules={{
            accept: ["image/png", "image/jpeg"],
            maxSizeInKB: 5000,
        }}
        validationErrors={validationErrors}
    />

    <MediaLibraryCollection
        name="downloads"
        initialValue={downloads}
        validationRules={{
            accept: ["image/png", "image/jpeg"],
            maxSizeInKB: 5000,
        }}
        validationErrors={validationErrors}
    />

    <button>Submit</button>
</form>
```

You can also set the maximum amount of images that users can be uploaded using the `max-items` prop. Don't forget to set the `multiple` prop for the attachment component.

```jsx
<form>
    <MediaLibraryAttachment name="files" maxItems={2} multiple />
    <MediaLibraryCollection name="downloads" maxItems={5} />

    <button>Submit</button>
</form>
```

See the [Validation rules section](#validation-rules) for a complete list of all possible validation rules.

### Checking the upload state

The components keep track of whether they're ready to be submitted, you can use this to disable a submit button while a file is still uploading or when there are frontend validation errors. This value can be tracked by passing a listener method to the `onIsReadyToSubmitChange` prop. If you submit a form while a file is uploading, Laravel will return a HTTP 500 error with an `invalid uuid` message.

```jsx
import { MediaLibraryAttachment } from "media-library-pro-react-attachment";

function AvatarComponent() {
    const [isReadyToSubmit, setIsReadyToSubmit] = useState(true);

    return(
        <MediaLibraryAttachment
            name="avatar"
            onIsReadyToSubmitChange={setIsReadyToSubmit}
        />

        <button disabled={!isReadyToSubmit} onClick={submit}>Submit</button>
    )
}
```

!(_partials/processing-uploads-on-the-server.md)
