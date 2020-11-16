<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewEnquiriesTest extends TestCase
{
    /** @test */
    public function a_user_can_view_all_of_their_enquiries()
    {
        $response = $this->getJson(route('api.enquiries.index'));
    }
}
