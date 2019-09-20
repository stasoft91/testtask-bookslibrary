<?php

namespace Tests\Feature;

use App\Helpers\ImageDownloader;
use Tests\TestCase;

class ImagesTest extends TestCase
{
    /** @test */
    public function test_downloader()
    {
        $filename = ImageDownloader::save('http://www.google.com/images/branding/googlelogo/1x/googlelogo_color_116x41dp.png');
        $this->assertFileExists(public_path('photos/' . $filename));
        unlink(public_path('photos/' . $filename));
    }

    /** @test */
    public function test_thumb_creator()
    {
        $filename = ImageDownloader::save('http://www.google.com/images/branding/googlelogo/1x/googlelogo_color_116x41dp.png');
        $this->assertFileExists(public_path('photos/' . $filename));

        $this->get('/photos/thumbs/200x400/' . $filename)->assertOk();
        $this->assertFileExists(public_path('/photos/thumbs/200x400/' . $filename));

        unlink(public_path('photos/' . $filename));
        unlink(public_path('/photos/thumbs/200x400/' . $filename));
    }
}
