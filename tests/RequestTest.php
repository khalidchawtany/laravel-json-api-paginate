<?php

namespace Spatie\JsonApiPaginate\Test;

class RequestTest extends TestCase
{
    /** @test */
    public function it_will_discover_the_page_size_parameter()
    {
        $response = $this->get('/?rows=2');

        $response->assertJsonFragment(['per_page' => 2]);
    }

    /** @test */
    public function it_will_discover_the_page_number_parameter()
    {
        $response = $this->get('/?page=2');

        $response->assertJsonFragment(['current_page' => 2]);
    }

    /** @test */
    public function it_will_use_the_default_page_size()
    {
        $response = $this->get('/');

        $response->assertJsonFragment(['per_page' => 10]);
    }

    /** @test */
    public function it_will_use_the_configured_page_size_parameter()
    {
        config(['json-api-paginate.row_parameter' => 'modified_size']);

        $response = $this->get('/?modified_size=2');

        $response->assertJsonFragment(['per_page' => 2]);
    }

    /** @test */
    public function it_will_use_the_configured_page_number_parameter()
    {
        config(['json-api-paginate.page_parameter' => 'modified_number']);

        $response = $this->get('/?modified_number=2');

        $response->assertJsonFragment(['current_page' => 2]);
    }

}
