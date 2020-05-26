<?php

namespace Tests\Browser;

use App\Helpers\Translator;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Helpers\VideoHelper;
use dawood\PhpScreenRecorder\ScreenRecorder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/')
                ->clickLink(Translator::phrase('login'))
                ->assertSee(Translator::phrase('login'))
                ->value('[name="email"]', 'keamsan.sem@gmail.com')
                ->value('[name="password"]', '123456')
                ->click('button[type="submit"]')
                ->assertSee(Translator::phrase('dashboard'));

                $browser->driver->takeScreenshot(base_path('tests/Browser/screenshots/1.png'));
        });
    }
}
