<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AjaxOnlyTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAjaxOnlyTest()
    {
        $response = $this->getAjax('/api/v1/items ');
        $response->assertStatus(200);
    }
}
