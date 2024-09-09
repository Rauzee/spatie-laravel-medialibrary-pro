---
title: Inertia
weight: 4
---

When using the components in repository that uses Inertia, the setup is very similar to the asynchronous setup.

```html
<template>
    <div>
        <media-library-attachment
            name="avatar"
            :initial-value="avatar"
            :validation-errors="validationErrors"
            @change="onChange"
        />

        <button @click="submitForm">Submit</button>
    </div>
</template>

<script>
    import { Inertia } from "@inertiajs/inertia";

    export default {
        data() {
            return {
                validationErrors: this.$page.props.errors,
                avatar: this.$page.props.values.avatar,
            };
        },

        methods: {
            onChange(avatar) {
                this.avatar = avatar;
            },

            submitForm() {
                Inertia.post("", { avatar: this.avatar });
            },
        },
    };
</script>
```
