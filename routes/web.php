<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('{folder}/thumbs/{width}x{height}/{filename}/{filename2?}', function($folder, $width, $height, $filenameOrDir, $filenameIfDir = null)
{
    // ЕСЛИ ТЫ ДОПИСАЛ СЮДА ЧТО-ТО НЕ ЗАБУДЬ ДОБАВИТЬ ЭТО В АВТОУДАЛЕНИЕ МОДЕЛИ (e.g. PostPhoto::class)
    $allowedSizes = collect([
        '200x400',
    ]);

    $allowedFolders = collect([
        'photos'
    ]);

    $filename = $filenameOrDir;

    if ($filenameIfDir !== null)
        $filename = $filenameOrDir . '/' . $filenameIfDir;

    if ( ! $allowedSizes->contains($width . 'x' . $height) )
        return abort(404);

    if ( ! $allowedFolders->contains($folder) )
        return abort(404);

    if ( ! File::exists(public_path($folder . '/' . $filename)) )
        return abort(404);

    if (! File::isDirectory(public_path($folder . '/thumbs/' . $width . 'x' . $height . '/'))) {
        File::makeDirectory(public_path($folder . '/thumbs/' . $width . 'x' . $height . '/'), 0755, true, true);
    }

    if ($filenameIfDir !== null) {
        if (!File::isDirectory(public_path($folder . '/thumbs/' . $width . 'x' . $height . '/' . $filenameOrDir))) {
            File::makeDirectory(public_path($folder . '/thumbs/' . $width . 'x' . $height . '/' . $filenameOrDir), 0755, true, true);
        }
    }

    return Image::make(public_path($folder.'/'.$filename))
        ->fit($width, $height)
        ->save(public_path($folder.'/thumbs/'.$width.'x'.$height.'/'.$filename))
        ->response();
});

Route::get('{path}', function () {
    return file_get_contents(public_path('_nuxt/index.html'));
})->where('path', '(.*)');
