## Storing the uploaded file(s)

Assuming you have properly setup the [Laravel Medialibrary package](https://spatie.be/docs/laravel-medialibrary/v11/introduction) your models will have some helper methods to help you store the files that are uploaded in the Medialibrary Pro components.

Imagine your have a Medialibrary Pro component with the name `avatar` your controller method could look like this:

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController
{
    public function store(Request $request)
    {
        $user = Auth::user();

        $user
            ->addFromMediaLibraryRequest($request->avatar)
            ->toMediaCollection('avatar');
    }
}
```

## Validation

The `ProfileController` we built assumes users will only upload the exact file types we're looking for. Of course they won't! We need to validate the incoming media before attaching them to our models.

The Media Library components provide instant client-side validation. You'll read more about that in the component docs. First, we'll set up server-side validation.

To validate uploaded media, we'll use a custom form request.

```diff
- public function store(Request $request)
+ public function store(ProfileRequest $request)
```
```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\MediaLibraryPro\Rules\Concerns\ValidatesMedia;

class ProfileRequest extends FormRequest
{
    use ValidatesMedia;

    public function rules()
    {
        return [
            'images' => $this
                ->validateMultipleMedia()
                ->minItems(1)
                ->maxItems(5)
                ->extension('png')
                ->maxItemSizeInKb(1024)
                ->attribute('name', 'required')
        ];
    }
}
```

---

Every component will pass data in a key of a request. The name of that key is the name you passed to the `name` prop of any of the components.

The content of that request key will be an array. For each file uploaded that array will hold an array with these keys.

- `name`: the name of the uploaded file
- `uuid`: the UUID of a `Media` model. For newly uploaded files that have not been associated to a model yet, the `Media` model will be associated with a `TemporaryUpload` model
- `order`: the order in which this item should be stored in a media collection.

## Validating requests

Even though the upload components do some client-side validation, we highly recommend always validating requests on the server as well.

You should handle validation in a form request. On the form request you should use the `Spatie\MediaLibraryPro\Rules\Concerns\ValidatesMedia` trait. This will give you access to the `validateSingleMedia` and `validateMultipleMedia` methods.

In this example we assume that a component was configured to use the `images` key of the request. We validate that there was at least one item uploaded, but no more than 5. Only `png`s that are up to 1MB in size are allowed. All images should have a name.

```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\MediaLibraryPro\Rules\Concerns\ValidatesMedia;

class MyRequest extends FormRequest
{
    use ValidatesMedia;

    public function rules()
    {
        return [
            'images' => $this
                ->validateMultipleMedia()
                ->minItems(1)
                ->maxItems(5)
                ->extension('png')
                ->maxItemSizeInKb(1024)
                ->attribute('name', 'required')
        ];
    }
}
```

If you are only allowing one uploaded file, you can use the `validateSingleMedia` in much the same way.

```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\MediaLibraryPro\Rules\Concerns\ValidatesMedia;

class MyRequest extends FormRequest
{
    use ValidatesMedia;

    public function rules()
    {
        return [
            'avatar' => $this
                ->validateSingleMedia()
                ->extension('png')
                ->maxItemSizeInKb(1024)
        ];
    }
}
```

These are the available validation methods on `validateSingleMedia() ` and `validateMultipleMedia()`

- `minSizeInKb($minSizeInKb)`: validates that a single upload is not smaller than the `$minSizeInKb` given.
- `maxSizeInKb($maxSizeInKb)`: validates that a single upload is not greater than the `$minSizeInKb` given.
- `extension($extension)`: this rule expects a single extension as a string or multiple extensions as an array. Under the hood, the rule will validate if the value has the mime type that corresponds with the given extension.
- `mime($mime)`: this rule expects a single mime type as a string or multiple mime types as an array.
- `itemName($rules)`: this rule accepts rules that should be used to validate the name of a media item.
- `customProperty($name, $rules)`: this rule accepts a custom property name and rules that should be used to validate the attribute.
- `dimensions($width, $height)`: validates that the image has a specific width and height (in pixels).
- `width($width)`: validates that the image has a specific width (in pixels). The height is not validated.
- `height($height)`: validates that the image has a specific height (in pixels). The width is not validated.
- `widthBetween($minWidth, $maxWidth)`: validates that the image width (in pixels) is between the `$minWidth` and `$maxWidth` given (inclusive)
- `heightBetween($minHeight, $maxHeight)`: validates that the image height (in pixels) is between the `$minHeight` and `$maxHeight` given (inclusive)

These rules can be used on `validateMultipleMedia()`;

- `minTotalSizeInKb($maxTotalSizeInKb)`: validates that the combined size of uploads is not smaller than the `$minTotalSizeInKb` given.
- `maxTotalSizeInKb($maxTotalSizeInKb)`: validates that the combined size of uploads is not greater than the `$maxTotalSizeInKb` given.

### Validating attributes and custom properties

If you're [using custom properties](/docs/laravel-medialibrary/v11/advanced-usage/using-custom-properties), you can validate them with the `customProperty` function. The first argument should be the name of the custom property you are validating. The second argument should be a string or an array with rules you'd like to use.

Here's an example where we validate `extra_property` and `another_extra_property`.

```php
use Illuminate\Foundation\Http\FormRequest;
use Spatie\MediaLibraryPro\Rules\Concerns\ValidatesMedia;

class StoreLivewireCollectionCustomPropertyRequest extends FormRequest
{
    use ValidatesMedia;

    public function rules()
    {
        return [
            'name' => 'required',
            'images' => $this->validateMultipleMedia()
                ->customProperty('extra_field', 'required|max:50')
                ->customProperty('another_extra_property', ['required', 'max:50'])
        ];
    }
}
```

## Processing requests

After you've validated the request, you should persist the changes to the media library. The media library provides two methods for that: `syncFromMediaLibraryRequest` and `addFromMediaLibraryRequest`. Both these methods are available on all [models that handle media](/docs/laravel-medialibrary/v11/basic-usage/preparing-your-model). Either way call the method `toMediaCollection` to update your media-model in the database. This will also ensure that temporary uploads are converted to the appropriate model.

### `addFromMediaLibraryRequest`

This method will add all media whose `uuid` is in the request to a media collection of a model. Existing media associated on the model will remain untouched.

You should probably use this method only when accepting new uploads.

```php
// in a controller

public function yourMethod(YourFormRequest $request)
{
    // retrieve model 

    $yourModel
        ->addFromMediaLibraryRequest($request->get('images'))
        ->toMediaCollection('images');

    flash()->success('Your model has been saved.')
    
    return back();
}
```

### `syncFromMediaLibraryRequest`

You should use this method when you are using the `x-media-library-collection` Blade component (or equivalent Vue or React component).

Here is an example where we are going to sync the contents of the `images` key in the request to the media library.
In this example we use the `images` key, but of course you should use the name that you used.

All media associated with `$yourModel` whose `uuid` is not present in the `images` array of the request will be deleted.

```php
// in a controller

public function yourMethod(YourFormRequest $request)
{
    // retrieve model 

    $yourModel
        ->syncFromMediaLibraryRequest($request->images)
        ->toMediaCollection('images');

    flash()->success('Your model has been saved.')
    
    return back();
}
```

After this code has been executed, the media, whose `uuid` is present in the `images` array of request, will be in the `images collection of `$yourModel`.

```php
$yourModel->getMedia('images'); // the media that we just synced will be returned.
```

### Handling custom properties

If you are using properties for your media items you should pass the names of the custom properties you expect to the `withCustomProperties` method. Only these custom properties will be accepted.

```php
$yourModel
    ->syncFromMediaLibraryRequest($request->images)
    ->withCustomProperties('extra_field', 'another_extra_field')
    ->toMediaCollection('images');
```

### Setting a name

If you want to use a specific media name before adding it to disk you can use the `usingName` method.

```php
$yourModel
    ->addFromMediaLibraryRequest($request->images)
    ->usingName('my custom name')
    ->toMediaCollection('images');
```

Alternatively, you can pass a callable to `usingName`. This callable accepts an instance of `Spatie\MediaLibraryPro\MediaLibraryRequestItem` which can be used to get properties of the uploaded file.

For this we have to add the `editableName` attribute to the component:

```html
<x-media-library-attachment name="images" editableName />
```

The component now will render an editable input field for the name.

In this example we're going to set the media name to the lowercase version of the uploaded filename before adding it the media library.

```php
$yourModel
    ->addFromMediaLibraryRequest($request->images)
    ->usingName(fn(MediaLibraryRequestItem $item) => strtolower($item->name))
    ->toMediaCollection('images');
```

### Setting a file name

If you want to rename an uploaded file before adding it to disk you can use the `usingFileName` method.

```php
$yourModel
    ->addFromMediaLibraryRequest($request->images)
    ->usingFileName('myFile.jpg')
    ->toMediaCollection('images');
```

Alternatively, you can pass a callable to `usingFileName`. This callable accepts an instance of `Spatie\MediaLibraryPro\MediaLibraryRequestItem` which can be used to get properties of the uploaded file.

In this example we're going to lowercase the name of the uploaded file before adding it the media library.

```php
$yourModel
    ->addFromMediaLibraryRequest($request->images)
    ->usingFileName(fn(MediaLibraryRequestItem $item) => strtolower($item->name))
    ->toMediaCollection('images');
```
