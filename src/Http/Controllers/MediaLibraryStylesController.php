<?php

namespace Spatie\MediaLibraryPro\Http\Controllers;

use Illuminate\Support\Facades\File;

class MediaLibraryStylesController
{
    public function __invoke()
    {
        //        $styles = File::get(__DIR__ . '../resources/js/media-library-pro-styles/dist/styles.css');

        $styles = File::get(__DIR__.'/../../../resources/js/media-library-pro-styles/dist/styles.css');
        $contents = <<<html
                   $styles
            html;

        return response()->make($contents)->header('Content-Type', 'text/css');
    }
}
