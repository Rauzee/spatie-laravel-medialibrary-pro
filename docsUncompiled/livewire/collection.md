---
title: Collection
weight: 3
---

## Administer the contents of a media library collection

You can manage the entire contents of a media library collection with `livewire:media-library` component. This
component is intended for use in admin sections.

Here is an example where we are going to administer an `images` collection of a `$blogPost` model. We assume that you
already [prepared the model](/docs/laravel-medialibrary/v11/basic-usage/preparing-your-model) to handle uploads.

```html
<form method="POST" wire:submit.prevent="submit">

    <input id="name" wire:model.debounce.500ms="name">

    <livewire:media-library
        collection="images"
        :model="$blogPost"
        wire:model="images"
    />

    <button type="submit">Submit</button>
</form>
```

In your Livewire component you must:
- use the `Spatie\MediaLibraryPro\Livewire\Concerns\WithMedia` trait
- add a public property `$images` that we can bind to upload to (you can use any name you want)
- pass the Eloquent model that the collection is saved on to the component, in this case `$blogPost`

Here is an example component:

```php
namespace App\Http\Livewire;

use App\Models\BlogPost;
use Livewire\Component;
use Spatie\MediaLibraryPro\Livewire\Concerns\WithMedia;

class MyForm extends Component
{
    use WithMedia;

    public $name;

    public $message = '';

    public $images;

    public $blogPost;

    public function mount()
    {
        $this->blogPost = BlogPost::first();
    }

    public function submit()
    {
        $this->blogPost->update(['name' => $this->name]);

        $this->blogPost
            ->addFromMediaLibraryRequest($this->images)
            ->toMediaCollection('images');

        $this->message = 'Your form has been submitted';       
    }

    public function render()
    {
        return view('livewire.my-form');
    }
}
```

### Validating the collection

You can pass any Laravel validation rule to the rules prop of the `livewire:media-library` component. Here's an example where only `jpeg` and `png` will be accepted.

```html
<livewire:media-library wire:model="images" collection="images" :model="$blogPost" rules="mimes:jpeg,png"/>
```

You can make the upload required by validating it in your Livewire component. Here's an example where at least one upload is required, but more than three uploads are not allowed.

```php
// in the method that handles the form submission inside your livewire component

public function submit()
{
    $this->validate([
        'images' => 'required|max:3',
    ]);
    
    // process the form submission
}
```

### Using custom properties

Media library supports [custom properties](/docs/laravel-medialibrary/v11/advanced-usage/using-custom-properties) to be saved on a media item. By
default, the  `livewire:media-library` component doesn't show the custom properties. To add them you should create a
blade view that will be used to display all form elements on a row in the component.

In this example we're going to add a custom property form field called `extra_field`.

```html
@include('medialibrary::livewire.partials.collection.fields')

<div class="media-library-field">
    <label class="media-library-label">Extra field</label>
    <input
        class="media-library-input"
        type="text"
        {{ $mediaItem->livewireCustomPropertyAttributes('extra_field') }}
    />

    @error($mediaItem->customPropertyErrorName('extra_field'))
    <span class="media-library-text-error">
       {{ $message }}
    </span>
    @enderror
</div>
```

You should then pass the path to that view to the `fields-view` prop of the `livewire:media-library` component.

```html
<livewire:media-library
    wire:model="images"
    :model="$formSubmission"
    collection="images"
    fields-view="app.your-custom-properties-blade-view-path"
/>
```

This is how that will look like.

![Screenshot of custom property](/docs/laravel-medialibrary-pro/v6/images/extra.png)

In your Livewire component, you can validate the custom properties like this. This example assumes that you have set the `name` attribute of `livewire:media-library` to `images`.

```php
// inside the method in your Livewire component that handles the form submission
public function submit()
{
    $this->validate([
        'images.*.custom_properties.extra_field' => 'required',
    ], ['required' => 'This field is required']);

    // process the form submission
}
```
