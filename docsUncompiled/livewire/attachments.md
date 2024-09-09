---
title: Attachments
weight: 2
---

## Handling a single upload

You can use `livewire:media-library` component to upload a single file.

![Screenshot of the attachment component](/docs/laravel-medialibrary-pro/v6/images/attachment.png)

Here's how that might look like in the view of your Livewire component:

```html
<form method="POST" wire:submit.prevent="submit">
   
    <input id="name" wire:model.debounce.500ms="name">

    <livewire:media-library wire:model="myUpload" />

    <button type="submit">Submit</button>
</form>
```

In your Livewire component you must:
- use the `Spatie\MediaLibraryPro\Livewire\Concerns\WithMedia` trait
- add a public property that defaults to an empty array for binding the media library component to (in the example above: `myUpload`, of course you can use any name you want)
- for each component that you are going to use you should add a public property with the name you use in the view for that component (in the example above: `myUpload`)

Here is an example component:

```php
namespace App\Http\Livewire;

use App\Models\YourModel;
use Livewire\Component;
use Spatie\MediaLibraryPro\Livewire\Concerns\WithMedia;

class MyForm extends Component
{
    use WithMedia;

    public $name;

    public $message = '';

    public $myUpload = [];

    public function submit()
    {
        $formSubmission = YourModel::create([
            'name' => $this->name,
        ]);

        $formSubmission
            ->addFromMediaLibraryRequest($this->myUpload)
            ->toMediaCollection('images');

        $this->message = 'Your form has been submitted';

        $this->name = '';
        $this->myUpload = null;
    }

    public function render()
    {
        return view('livewire.my-form');
    }
}
```

Immediately after a file has been uploaded it will be stored as a temporary upload.  In the method that handles the form submission you must use the `addFromMediaLibraryRequest` method to move the uploaded file to the model you want.

To clear out an uploaded file from being displayed, you can set bound property `myUpload` to `null`. This will only clear the uploaded file from view, uploaded files will not be deleted.

### Validating a single upload

You can pass any Laravel validation rule to the rules prop of the `livewire:media-library` component. Here's an example where only `jpeg` and `png` will be accepted.

```html
<livewire:media-library name="myUpload" rules="mimes:jpeg,png"/>
```

You can make the upload required by validating it in your Livewire component:

```php
// in the method that handles the form submission inside your livewire component

public function submit()
{
    $this->validate([
        'myUpload' => 'required',
    ]);
    
    // process the form submission
}
```

## Handling multiple uploads

Uploading multiple files is very similar to uploading a single file. The only thing you need to add is a `multiple` property

```html
<form method="POST" wire:submit.prevent="submit">
   
    <input id="name" wire:model.debounce.500ms="name">

    <livewire:media-library wire:model="images" multiple />

    <button type="submit">Submit</button>
</form>
```

![Screenshot of the attachment component](/docs/laravel-medialibrary-pro/v6/images/multiple.png)

In your Livewire component you must:
- use the `Spatie\MediaLibraryPro\Livewire\Concerns\WithMedia` trait
- add a public property `$images` that we can bind the uploads to


Here is an example component:

```php
namespace App\Http\Livewire;

use App\Models\YourModel;
use Livewire\Component;
use Spatie\MediaLibraryPro\Livewire\Concerns\WithMedia;

class MyForm extends Component
{
    use WithMedia;

    public $name;

    public $message = '';

    public $images = [];

    public function submit()
    {
        $formSubmission = YourModel::create([
            'name' => $this->name,
        ]);

        $formSubmission
            ->addFromMediaLibraryRequest($this->images)
            ->toMediaCollection('images');

        $this->message = 'Your form has been submitted';

        $this->name = '';
        
        $this->images = [];
    }

    public function render()
    {
        return view('livewire.my-form');
    }
}
```

### Validating multiple uploads

You can pass any Laravel validation rule to the rules prop of the `livewire:media-library` component. Here's an example where only `jpeg` and `png` will be accepted.

```html
<livewire:media-library wire:model="images" rules="mimes:jpeg,png"/>
```

You can make the upload required by validating it in your Livewire component. Here's an example where at least one upload is required, but more than three uploads are not allowed.

```php
// in the method that handles the form submission inside your Livewire component

public function submit()
{
    $this->validate([
        'images' => 'required|max:3',
    ]);
    
    // process the form submission
}
```
