---
title: Inertia
weight: 4
---

When using the components in repository that uses Inertia, the setup is very similar to the asynchronous setup.

```jsx
import React, { useState } from "react";
import { MediaLibraryAttachment } from "media-library-pro-react-attachment";
import { usePage } from "@inertiajs/inertia-react";
import { Inertia } from "@inertiajs/inertia";

export default function AccountPage() {
    const { props } = usePage();
    const [avatar, setAvatar] = useState(props.values.avatar);

    function handleSubmit() {
        Inertia.post("", { avatar });
    }

    return (
        <div>
            <MediaLibraryAttachment
                name="avatar"
                initialValue={avatar}
                errors={props.errors}
                onChange={setAvatar}
            />

            <button type="button" onClick={handleSubmit}>
                Submit
            </button>
        </div>
    );
}
```
