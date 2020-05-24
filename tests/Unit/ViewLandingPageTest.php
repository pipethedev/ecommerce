<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ViewLandingPageTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function landing_page_loads_correctly()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Nike New');
    }
}
